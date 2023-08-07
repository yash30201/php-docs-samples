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
 * For instructions on how to run the samples:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/media/livestream/README.md
 */

namespace Google\Cloud\Samples\Media\LiveStream;

// [START livestream_list_channels]
use Google\Cloud\Video\LiveStream\V1\Client\LivestreamServiceClient;
use Google\Cloud\Video\LiveStream\V1\ListChannelsRequest;

/**
 * Lists the channels for a given location.
 *
 * @param string  $callingProjectId   The project ID to run the API call under
 * @param string  $location           The location of the channels
 */
function list_channels(
    string $callingProjectId,
    string $location
): void {
    // Instantiate a client.
    $livestreamClient = new LivestreamServiceClient();
    $parent = $livestreamClient->locationName($callingProjectId, $location);
    $request = (new ListChannelsRequest())
        ->setParent($parent);

    $response = $livestreamClient->listChannels($request);
    // Print the channel list.
    $channels = $response->iterateAllElements();
    print('Channels:' . PHP_EOL);
    foreach ($channels as $channel) {
        printf('%s' . PHP_EOL, $channel->getName());
    }
}
// [END livestream_list_channels]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
