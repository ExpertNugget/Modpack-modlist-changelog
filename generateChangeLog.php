<?php
if (isset($_FILES['previousModpack']) && isset($_FILES['currentModpack'])) {
    // Extract the zip files
    $previousModpack = new ZipArchive();
    @$previousModpack->open($_FILES['previousModpack']['tmp_name']);
    $previousModpack->extractTo('previousModpack');

    $currentModpack = new ZipArchive();
    @$currentModpack->open($_FILES['currentModpack']['tmp_name']);
    $currentModpack->extractTo('currentModpack');

    // Read the manifest.json files
    $previousManifest = json_decode(file_get_contents('previousModpack/manifest.json'), true);
    $currentManifest = json_decode(file_get_contents('currentModpack/manifest.json'), true);

    // Generate the change-log
    $added = '';
    $removed = '';
    $updated = '';
    $downgraded = '';
    if ($currentManifest['minecraft']['version'] != $previousManifest['minecraft']['version']) {
        $updated .= '- <span style="color:green">Changed to Minecraft version: ' . $currentManifest['minecraft']['version'] . '</span>' . PHP_EOL;
    }
    if (isset($currentManifest['minecraft']['modLoaders'][0]['id'])) {
        $currentForgeVersion = explode('-', $currentManifest['minecraft']['modLoaders'][0]['id'])[1];
        $previousForgeVersion = explode('-', $previousManifest['minecraft']['modLoaders'][0]['id'])[1];
        if ($previousForgeVersion != $currentForgeVersion) {
            $updated .= '- <span style="color:green">Changed to Forge version: ' . $currentForgeVersion . '</span>' . PHP_EOL;
        }
    }

    foreach ($previousManifest['files'] as $previousMod) {
        $found = false;
        foreach ($currentManifest['files'] as $currentMod) {
            if ($previousMod['projectID'] == $currentMod['projectID']) {
                $found = true;
                if ($previousMod['fileID'] != $currentMod['fileID']) {
                    if ($previousMod['fileID'] > $currentMod['fileID']) {
                        $downgraded .= '- <span style="color:orange">Downgraded ' . $previousMod['projectID'] . '</span>' . PHP_EOL;
                    } else {
                        $updated .= '- <span style="color:green">Updated ' . $previousMod['projectID'] . '</span>' . PHP_EOL;
                    }
                }
            }
        }
        if (!$found) {
            $removed .= '- <span style="color:red">Removed ' . $previousMod['projectID'] . '</span>' . PHP_EOL;
        }
    }
    foreach ($currentManifest['files'] as $currentMod) {
        $found = false;
        foreach ($previousManifest['files'] as $previousMod) {
            if ($currentMod['projectID'] == $previousMod['projectID']) {
                $found = true;
            }
        }
        if (!$found) {
            $added .= '- <span style="color:blue">Added ' . $currentMod['projectID'] . '</span>' . PHP_EOL;
        }
    }

    // Display the change-log
    echo '<a href="index.php" class="btn btn-default" role="button">Back</a>
          <div class="container">
              <h2>Change-Log</h2>
              <pre>' .
              ($added != '' ? '## Added' . PHP_EOL . $added : '') .
              ($updated != '' ? '## Updated' . PHP_EOL . $updated : '') .
              ($removed != '' ? '## Removed' . PHP_EOL . $removed : '') .
              ($downgraded != '' ? '## Downgraded' . PHP_EOL . $downgraded : '') .
              '</pre>
          </div>';
}
?>

<style>
    pre {
        background-color: #f5f5f5;
        padding: 10px;
    }
</style>
