<?php
require FEATURES_DIRECTORY . '/schedules/ScheduleService.php';
require FEATURES_DIRECTORY . '/subjects/SubjectService.php';

$scheduleService = new ScheduleService($connection);
$subjectService = new SubjectService($connection);

$subjects = $subjectService->getSubjects();

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"]) && $_POST['action'] == 'schedule-edit') {
    $request = $_POST;

    $schedule = $scheduleService->getScheduleById(isset($_POST['id']) ? $_POST['id'] : 0);
    if (!$schedule) {
        apiResponse(false, null, [REQUEST_RESOURCE_NOT_FOUND => "Schedule not found."]);
    }

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
        apiResponse(false, null, $errors);
    }

    $schedule->update($subject_id, $day, $start_time, $end_time);
    try {
        $result = $scheduleService->update($schedule);
        if ($result) {
            apiResponse(true, $schedule);
        } else {
            throw new Exception(INSERT_FAILED);
        }
    } catch (Exception $ex) {
        apiResponse(false, null, [INSERT_FAILED => "Cannot process request, an unexpected error occurred."]);
    }
}

$selectedSchedule = null;
if ($_SERVER['REQUEST_METHOD'] === HTTP_GET) {
    if (!isset($_GET["id"]) || $_GET['id'] <= 0) {
        $_SESSION[REQUEST_RESOURCE_NOT_FOUND] = "Schedule not found.";
        navigateTo($routes, "schedules");
    }
    $request = $_GET;

    $selectedSchedule = $scheduleService->getScheduleById($request["id"]);
    if (!$selectedSchedule) {
        $_SESSION[REQUEST_RESOURCE_NOT_FOUND] = "Schedule not found.";
        navigateTo($routes, "schedules");
    }
}

$pageTitle = "Subject Schedules";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">Edit Schedule</div>
        <div class="card-body">
            <form method="post" id="form_schedule_edit">
                <input type="hidden" name="action" value="schedule-edit" readonly required>
                <input type="hidden" name="id" value="<?= isset($_GET["id"]) ? $_GET["id"] : 0 ?>" readonly required>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1">Subject</label>
                            <select class="form-select"
                                aria-label="Default select example"
                                name="subject_id"
                                id="input_subject_id">
                                <option selected>Select subject</option>
                                <?php
                                if (isset($subjects) || !empty($subjects)) {
                                    $oldSelected = isset($selectedSchedule) ? $selectedSchedule->subject_id : getOldFormValue("subject_id");
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
                            <div class='invalid-feedback d-block' id="input_subject_idError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="input_day">Schedule Day</label>
                            <input class="form-control"
                                id="input_day"
                                name="day"
                                type="text"
                                placeholder="Enter subject title"
                                value="<?= isset($selectedSchedule) ? $selectedSchedule->day : getOldFormValue("day") ?>" />
                            <div class='invalid-feedback d-block' id="input_dayError"></div>
                        </div>
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="input_start_time">Start Time</label>
                            <input class="form-control"
                                id="input_start_time"
                                name="start_time"
                                type="time"
                                placeholder="Enter start time"
                                value="<?= isset($selectedSchedule) ? $selectedSchedule->startToString() : getOldFormValue("start_time") ?>" />
                            <div class='invalid-feedback d-block' id="input_start_timeError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="input_end_time">End Time</label>
                            <input class="form-control"
                                id="input_end_time"
                                name="end_time"
                                type="time"
                                placeholder="Enter end time"
                                value="<?= isset($selectedSchedule) ? $selectedSchedule->endToString() : getOldFormValue("end_time") ?>" />
                            <div class='invalid-feedback d-block' id="input_end_timeError"></div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Update Schedule</button>
                <a class="btn btn-danger btn-block" href="<?= getRouteUrl($routes, "schedules") ?>" id="btn_cancel">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$scripts = ob_start();
?>
<script>
    $(document).ready(function() {
        $("#form_schedule_edit").submit(function(e) {
            e.preventDefault();
            removeFormValidationErrors("input_subject_id", "is-invalid");
            removeFormValidationErrors("input_day", "is-invalid");
            removeFormValidationErrors("input_start_time", "is-invalid");
            removeFormValidationErrors("input_end_time", "is-invalid");

            const formArray = $(this).serializeArray();
            const formData = {};
            formArray.forEach(field => {
                formData[field.name] = field.value;
            });

            $.ajax({
                type: "post",
                url: routes["schedules-edit"].url,
                data: {
                    action: formData.action,
                    id: formData.id,
                    subject_id: formData.subject_id,
                    day: formData.day,
                    start_time: formData.start_time,
                    end_time: formData.end_time,
                },
                dataType: "json",
                success: function(response) {
                    if (!response.success) {
                        if (response.error["subject_id"]) {
                            showFormValidationErrors("input_subject_id", response.error["subject_id"])
                        }
                        if (response.error["day"]) {
                            showFormValidationErrors("input_day", response.error["day"])
                        }
                        if (response.error["start_time"]) {
                            showFormValidationErrors("input_start_time", response.error["start_time"])
                        }
                        if (response.error["end_time"]) {
                            showFormValidationErrors("input_end_time", response.error["end_time"])
                        }
                        if (response.error["INSERT_FAILED"]) {
                            Swal.fire({
                                title: "Update Failed",
                                text: response.error["INSERT_FAILED"],
                                icon: "error"
                            });
                        }
                    } else {
                        Swal.fire({
                            title: "Update Success",
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
                    }
                }
            });
        })
    });
</script>

<?php
$scripts = ob_get_clean();
include INCLUDES_DIRECTORY . '/layouts/protected_layout.php';
?>
