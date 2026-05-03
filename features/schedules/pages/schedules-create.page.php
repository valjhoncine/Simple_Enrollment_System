<?php
require FEATURES_DIRECTORY . '/schedules/ScheduleService.php';
require FEATURES_DIRECTORY . '/subjects/SubjectService.php';

const SCHEDULES_VALIDATION_ERRORS = "SCHEDULES_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(SCHEDULES_VALIDATION_ERRORS);

$scheduleService = new ScheduleService($connection);
$subjectService = new SubjectService($connection);

$subjects = $subjectService->getSubjects();

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"]) && $_POST['action'] === 'schedule') {
    $request = $_POST;

    $subject_id = trim($request["subject_id"]);
    $day = trim($request["day"]);
    $start_time = trim($request["start_time"]);
    $end_time = trim($request["end_time"]);

    if (!array_key_exists($subject_id, $subjects) || $subject_id <= 0) {
        $errors["subject_id"][] = "Subject is required.";
    }
    if ($day == "") {
        $errors["day"][] = "Subject day is required.";
    }
    if ($start_time == "") {
        $errors["start_time"][] = "Subject start time is required.";
    }
    if ($end_time == "") {
        $errors["end_time"][] = "Subject end time is required.";
    }

    if (!empty($errors)) {
        $_SESSION[SCHEDULES_VALIDATION_ERRORS] = $errors;
        $_SESSION[OLD_FORM_VAL] = [
            "subject_id" => $subject_id,
            "day" => $day,
            "start_time" => $start_time,
            "end_time" => $end_time,
        ];
        navigateTo($routes, "schedules-create");
    }

    $result = $scheduleService->save($subject_id,$day,$start_time,$end_time);
    if ($result) {
        $_SESSION[INSERT_SUCCESS] = "Schedule created successfully.";
        navigateTo($routes, "schedules-create");
    } else {
        $_SESSION[INSERT_FAILED] = "An error occurred failed to create, please try again.";
        navigateTo($routes, "schedules-create");
    }
}

$pageTitle = "Subject Schedules";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">Add New Schedule</div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="schedule" readonly required>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1">Subject</label>
                            <select class="form-select <?= setFormFieldIsInvalid($errors, "subject_id") ?>"
                                aria-label="Default select example"
                                name="subject_id">
                                <option selected>Select subject</option>
                                <?php
                                if (isset($subjects) || !empty($subjects)) {
                                    $oldSelected = getOldFormValue("subject_id");
                                    foreach ($subjects as $subject) {
                                        if ($subject->id > 1) {
                                ?>
                                            <option
                                                value="<?= $subject->id ?>"
                                                <?php
                                                if ((int)$oldSelected === $subject->id) {
                                                    echo 'selected';
                                                }
                                                ?>>
                                                <?= htmlspecialchars($subject->code) . ' - ' . htmlspecialchars($subject->name) ?></option>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <?php displayError($errors, "subject_id"); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="inputScheduleDay">Schedule Day</label>
                            <input class="form-control <?= setFormFieldIsInvalid($errors, "day") ?>"
                                id="inputScheduleDay"
                                name="day"
                                type="text"
                                placeholder="Enter subject title"
                                value="<?= getOldFormValue("day") ?>" />
                            <?php displayError($errors, "day"); ?>
                        </div>
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="inputStartTime">Start Time</label>
                            <input class="form-control <?= setFormFieldIsInvalid($errors, "start_time") ?>"
                                id="inputStartTime"
                                name="start_time"
                                type="time"
                                placeholder="Enter start time"
                                value="<?= getOldFormValue("start_time") ?>" />
                            <?php displayError($errors, "start_time"); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="inputEndTime">End Time</label>
                            <input class="form-control <?= setFormFieldIsInvalid($errors, "end_time") ?>"
                                id="inputEndTime"
                                name="end_time"
                                type="time"
                                placeholder="Enter end time"
                                value="<?= getOldFormValue("end_time") ?>" />
                            <?php displayError($errors, "end_time"); ?>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Create Schedule</button>
                <a class="btn btn-danger btn-block" href="<?= getRouteUrl($routes, "schedules") ?>" id="btn_cancel">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$scripts = ob_start();
?>


<?php
if (isset($_SESSION[INSERT_SUCCESS])) {
    $ifMes = $_SESSION[INSERT_SUCCESS];
    unset($_SESSION[INSERT_SUCCESS]);
?>
    <script>
        Swal.fire({
            title: "Create Success",
            text: "<?= $ifMes ?>",
            icon: "success",
            showCancelButton: true,
            confirmButtonText: "Ok",
            cancelButtonText: 'Go to Schedules Page'
        }).then(function(result) {
            if (result.dismiss === Swal.DismissReason.cancel) {
                console.log(result)
                $("#btn_cancel")[0].click();
            }
        });
    </script>
<?php
}
?>

<?php
if (isset($_SESSION[INSERT_FAILED])) {
    $ifMes = $_SESSION[INSERT_FAILED];
    unset($_SESSION[INSERT_FAILED]);
?>
    <script>
        Swal.fire({
            title: "Create Failed",
            text: "<?= $ifMes ?>",
            icon: "error"
        });
    </script>
<?php
}
?>

<?php
$scripts = ob_get_clean();
include INCLUDES_DIRECTORY . '/layouts/protected_layout.php';
?>
