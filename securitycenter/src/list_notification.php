<?php
/**
 * Copyright 2020 Google LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the 'License');
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an 'AS IS' BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Samples\SecurityCenter;

// [START securitycenter_list_notification_configs]
use Google\Cloud\SecurityCenter\V1\SecurityCenterClient;

/**
 * @param string $organizationId        Your org ID
 */
function list_notification(string $organizationId): void
{
    $securityCenterClient = new SecurityCenterClient();
    // 'parent' must be in one of the following formats:
    //		"organizations/{orgId}"
    //		"projects/{projectId}"
    //		"folders/{folderId}"
    $parent = $securityCenterClient::organizationName($organizationId);

    foreach ($securityCenterClient->listNotificationConfigs($parent) as $element) {
        printf('Found notification config %s' . PHP_EOL, $element->getName());
    }

    print('Notification configs were listed' . PHP_EOL);
}
// [END securitycenter_list_notification_configs]

// The following 2 lines are only needed to execute the samples on the CLI
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
