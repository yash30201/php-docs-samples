<?php

/**
 * Copyright 2023 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * For instructions on how to run the samples:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/main/dlp/README.md
 */

namespace Google\Cloud\Samples\Dlp;

# [START dlp_deidentify_table_condition_infotypes]
use Google\Cloud\Dlp\V2\DlpServiceClient;
use Google\Cloud\Dlp\V2\PrimitiveTransformation;
use Google\Cloud\Dlp\V2\InfoType;
use Google\Cloud\Dlp\V2\DeidentifyConfig;
use Google\Cloud\Dlp\V2\InfoTypeTransformations\InfoTypeTransformation;
use Google\Cloud\Dlp\V2\InfoTypeTransformations;
use Google\Cloud\Dlp\V2\ContentItem;
use Google\Cloud\Dlp\V2\Value;
use Google\Cloud\Dlp\V2\Table;
use Google\Cloud\Dlp\V2\Table\Row;
use Google\Cloud\Dlp\V2\FieldId;
use Google\Cloud\Dlp\V2\ReplaceWithInfoTypeConfig;
use Google\Cloud\Dlp\V2\FieldTransformation;
use Google\Cloud\Dlp\V2\RecordCondition;
use Google\Cloud\Dlp\V2\RecordCondition\Condition;
use Google\Cloud\Dlp\V2\RecordCondition\Conditions;
use Google\Cloud\Dlp\V2\RecordCondition\Expressions;
use Google\Cloud\Dlp\V2\RecordTransformations;
use Google\Cloud\Dlp\V2\RelationalOperator;

/**
 * De-identify table data using conditional logic and replace with infoTypes.
 * Transform findings only when specific conditions are met on another field.
 *
 * @param string $callingProjectId      The Google Cloud project id to use as a parent resource.
 * @param string $inputCsvFile          The input file(csv) path  to deidentify
 * @param string $outputCsvFile         The oupt file path to save deidentify content
 */

function deidentify_table_condition_infotypes(
    // TODO(developer): Replace sample parameters before running the code.
    string $callingProjectId,
    string $inputCsvFile = './test/data/table1.csv',
    string $outputCsvFile = './test/data/deidentify_table_condition_infotypes_output.csv'
): void {
    // Instantiate a client.
    $dlp = new DlpServiceClient();

    $parent = "projects/$callingProjectId/locations/global";

    // Read a CSV file
    $csvLines = file($inputCsvFile, FILE_IGNORE_NEW_LINES);
    $csvHeaders = explode(',', $csvLines[0]);
    $csvRows = array_slice($csvLines, 1);

    // Convert CSV file into protobuf objects
    $tableHeaders = array_map(function ($csvHeader) {
        return (new FieldId)
            ->setName($csvHeader);
    }, $csvHeaders);

    $tableRows = array_map(function ($csvRow) {
        $rowValues = array_map(function ($csvValue) {
            return (new Value())
                ->setStringValue($csvValue);
        }, explode(',', $csvRow));
        return (new Row())
            ->setValues($rowValues);
    }, $csvRows);

    // Construct the table object
    $tableToDeIdentify = (new Table())
        ->setHeaders($tableHeaders)
        ->setRows($tableRows);

    // Specify what content you want the service to de-identify.
    $content = (new ContentItem())
        ->setTable($tableToDeIdentify);

    // Specify the type of info the inspection will look for.
    $personNameInfoType = (new InfoType())
        ->setName('PERSON_NAME');

    // Specify that findings should be replaced with corresponding info type name.
    $primitiveTransformation = (new PrimitiveTransformation())
        ->setReplaceWithInfoTypeConfig(new ReplaceWithInfoTypeConfig());

    // Associate info type with the replacement strategy
    $infoTypeTransformation = (new InfoTypeTransformation())
        ->setPrimitiveTransformation($primitiveTransformation)
        ->setInfoTypes([$personNameInfoType]);

    $infoTypeTransformations = (new InfoTypeTransformations())
        ->setTransformations([$infoTypeTransformation]);

    // Specify fields to be de-identified.
    $fieldIds = [
        (new FieldId())->setName('PATIENT'),
        (new FieldId())->setName('FACTOID'),
    ];

    // Specify when the above fields should be de-identified.
    $condition = (new Condition())
        ->setField((new FieldId())
            ->setName('AGE'))
        ->setOperator(RelationalOperator::GREATER_THAN)
        ->setValue((new Value())
            ->setIntegerValue(89));

    // Apply the condition to records
    $recordCondition = (new RecordCondition())
        ->setExpressions((new Expressions())
                ->setConditions((new Conditions())
                        ->setConditions([$condition])
                )
        );

    // Associate the de-identification and conditions with the specified fields.
    $fieldTransformation = (new FieldTransformation())
        ->setInfoTypeTransformations($infoTypeTransformations)
        ->setFields($fieldIds)
        ->setCondition($recordCondition);

    $recordtransformations = (new RecordTransformations())
        ->setFieldTransformations([$fieldTransformation]);

    $deidentifyConfig = (new DeidentifyConfig())
        ->setRecordTransformations($recordtransformations);

    // Run request
    $response = $dlp->deidentifyContent([
        'parent' => $parent,
        'deidentifyConfig' => $deidentifyConfig,
        'item' => $content
    ]);

    // Print results
    $csvRef = fopen($outputCsvFile, 'w');
    fputcsv($csvRef, $csvHeaders);
    foreach ($response->getItem()->getTable()->getRows() as $tableRow) {
        $values = array_map(function ($tableValue) {
            return $tableValue->getStringValue();
        }, iterator_to_array($tableRow->getValues()));
        fputcsv($csvRef, $values);
    };
    printf($outputCsvFile);
}
# [END dlp_deidentify_table_condition_infotypes]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
