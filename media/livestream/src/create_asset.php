<?php

/**
 * Copyright 2023 Google LLC.
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
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/media/livestream/README.md
 */

namespace Google\Cloud\Samples\Media\LiveStream;

// [START livestream_create_asset]
use Google\Cloud\Video\LiveStream\V1\Asset;
use Google\Cloud\Video\LiveStream\V1\Client\LivestreamServiceClient;
use Google\Cloud\Video\LiveStream\V1\CreateAssetRequest;

/**
 * Creates an asset. You can use an asset to create a slate.
 *
 * @param string  $callingProjectId   The project ID to run the API call under
 * @param string  $location           The location of the asset
 * @param string  $assetId            The ID of the asset to be created
 * @param string  $assetUri           The Cloud Storage URI of the asset
 */
function create_asset(
    string $callingProjectId,
    string $location,
    string $assetId,
    string $assetUri
): void {
    // Instantiate a client.
    $livestreamClient = new LivestreamServiceClient();

    $parent = $livestreamClient->locationName($callingProjectId, $location);
    $asset = (new Asset())
        ->setVideo(
            (new Asset\VideoAsset())
                ->setUri($assetUri));

    // Run the asset creation request. The response is a long-running operation ID.
    $request = (new CreateAssetRequest())
        ->setParent($parent)
        ->setAsset($asset)
        ->setAssetId($assetId);
    $operationResponse = $livestreamClient->createAsset($request);
    $operationResponse->pollUntilComplete();
    if ($operationResponse->operationSucceeded()) {
        $result = $operationResponse->getResult();
        // Print results
        printf('Asset: %s' . PHP_EOL, $result->getName());
    } else {
        $error = $operationResponse->getError();
        // handleError($error)
    }
}
// [END livestream_create_asset]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
