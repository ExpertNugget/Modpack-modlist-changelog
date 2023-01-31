
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script>
        function enableGenerateLogsButton() {
            let previousModpack = document.getElementById("previousModpack").value;
            let currentModpack = document.getElementById("currentModpack").value;
            if (previousModpack !== "" && currentModpack !== "") {
                document.getElementById("generateLogsButton").disabled = false;
            } else {
                document.getElementById("generateLogsButton").disabled = true;
            }
        }
    </script>
</head>
<body>
    <div class="container" style="text-align: center;">
        <h1><?php echo $pageTitle; ?></h1>
        <form action="generateChangeLog.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label for="previousModpack">Previous Modpack:</label>
                    <input type="file" name="previousModpack" id="previousModpack" accept=".zip" oninput="enableGenerateLogsButton()" />
                </div>
                <div class="col-md-6">
                    <label for="currentModpack">Current Modpack:</label>
                    <input type="file" name="currentModpack" id="currentModpack" accept=".zip" oninput="enableGenerateLogsButton()" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <input type="submit" value="Generate Change-Log" id="generateLogsButton" disabled />
                </div>
            </div>
        </form>
    </div>
</body>
</html>