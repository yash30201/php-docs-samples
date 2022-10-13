<?php
/**
 * Copyright 2022 Google LLC.
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
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/bigquery/api/README.md
 */

namespace Google\Cloud\Samples\BigQuery;

# [START bigquery_add_column_load_append]
use Google\Cloud\BigQuery\BigQueryClient;

/**
 * Adds a new column to a BigQuery table while appending rows using a load job.
 *
 * @param string $projectId The Google project ID
 * @param string $datasetId The BigQuery dataset ID
 * @param string $tableId The table ID
 */
function add_column_load_append(string $projectId, string $datasetId, string $tableId): void
{
    $bigQuery = new BigQueryClient([
      'projectId' => $projectId,
    ]);
    $dataset = $bigQuery->dataset($datasetId);
    $table = $dataset->table($tableId);
    // In this example, the existing table contains only the 'Name' and 'Title'.
    // A new column 'Description' gets added after load job.

    $schema = [
      'fields' => [
          ['name' => 'name', 'type' => 'string', 'mode' => 'nullable'],
          ['name' => 'title', 'type' => 'string', 'mode' => 'nullable'],
          ['name' => 'description', 'type' => 'string', 'mode' => 'nullable']
      ]
    ];

    // $options = [
    //   'schema' => $schema,
    //   'writeDisposition' => 'WRITE_APPEND',
    //   'schemaUpdateOptions' => ['ALLOW_FIELD_ADDITION']
    // ];

    $loadConfig = $table->load(fopen('../test/data/test_data_extra_column.csv', 'r'), )
    ->destinationTable($table)->schema($schema)
    ->schemaUpdateOptions(['ALLOW_FIELD_ADDITION'])
    ->sourceFormat('CSV')
    ->writeDisposition('WRITE_APPEND');

    $job = $bigQuery->runJob($loadConfig);

    if($job->isComplete()) {
      printf('Success');
    }

}
# [END bigquery_add_column_load_append]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
