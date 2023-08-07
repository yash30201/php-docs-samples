<?php

/**
 * Copyright 2021 Google Inc.
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
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/main/media/transcoder/README.md
 */

namespace Google\Cloud\Samples\Media\Transcoder;

# [START transcoder_delete_job]
use Google\Cloud\Video\Transcoder\V1\Client\TranscoderServiceClient;
use Google\Cloud\Video\Transcoder\V1\DeleteJobRequest;

/**
 * Deletes a Transcoder job.
 *
 * @param string $projectId The ID of your Google Cloud Platform project.
 * @param string $location The location of the job.
 * @param string $jobId The job ID.
 */
function delete_job($projectId, $location, $jobId)
{
    // Instantiate a client.
    $transcoderServiceClient = new TranscoderServiceClient();

    $formattedName = $transcoderServiceClient->jobName($projectId, $location, $jobId);
    $request = (new DeleteJobRequest())
        ->setName($formattedName);
    $transcoderServiceClient->deleteJob($request);

    print('Deleted job' . PHP_EOL);
}
# [END transcoder_delete_job]

require_once __DIR__ . '/../../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
