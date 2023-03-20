<?php
/**
 * Copyright 2022 Google LLC
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
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/main/spanner/README.md
 */

namespace Google\Cloud\Samples\Spanner;

// [START spanner_insert_dml_returning]
use Google\Cloud\Spanner\SpannerClient;

/**
 * Inserts sample data into the given database using DML returning.
 *
 * @param string $instanceId The Spanner instance ID.
 * @param string $databaseId The Spanner database ID.
 */
function insert_dml_returning(string $instanceId, string $databaseId): void
{
    $spanner = new SpannerClient();
    $instance = $spanner->instance($instanceId);
    $database = $instance->database($databaseId);

    // Insert records into SINGERS table and returns the generated column
    // FullName of the inserted records using ‘THEN RETURN FullName’. It is also
    // possible to return all columns of all the inserted records by using
    // ‘THEN RETURN *’.

    $sql = 'INSERT INTO Singers (SingerId, FirstName, LastName) '
        . "VALUES (12, 'Melissa', 'Garcia'), "
        . "(13, 'Russell', 'Morales'), "
        . "(14, 'Jacqueline', 'Long'), "
        . "(15, 'Dylan', 'Shaw') "
        . 'THEN RETURN FullName';

    $transaction = $database->transaction();
    $result = $transaction->execute($sql);
    foreach ($result->rows() as $row) {
        printf(
            '%s inserted.' . PHP_EOL,
            $row['FullName'],
        );
    }
    printf(
        'Inserted row(s) count: %d' . PHP_EOL,
        $result->stats()['rowCountExact']
    );
    $transaction->commit();
}
// [END spanner_insert_dml_returning]
// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
