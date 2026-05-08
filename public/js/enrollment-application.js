let tableEnrollmentApplications
$(document).ready(function () {
    initializeEnrollmentApplicationTable()
    apiGetEnrollmentApplications();
});
function initializeEnrollmentApplicationTable() {
    tableEnrollmentApplications = $("#tableEnrollmentApplications").DataTable({
        processing: true,
        scrollX: true,
        columns: [
            {
                data: null, render: function (d, t, r, meta) {
                    return meta.row + 1
                }
            },
            { data: 'user_id', title: 'User Id', visible: false },
            { data: 'first_name', title: 'First Name', },
            { data: 'last_name', title: 'Last Name', },
            { data: 'student_number', title: 'Student Number', },
            { data: 'course_title', title: 'Program/Course', },
            {
                data: null,
                title: 'Enrollment Status',
                orderable: false,
                searchable: false,
                render: function (rowData) {
                    if (rowData.enrollment_status === 0) {
                        return `Pending Approval`
                    }
                    return 'Approved'
                }
            },
            {
                data: null,
                title: 'Actions',
                orderable: false,
                searchable: false,
                render: function (rowData) {
                    if (rowData.enrollment_status === 0) {
                        return `
                                <button class="btn btn-success" data-id="${rowData.enrollment_id}" id="btn_approve_enrollment">Approve</button> <br><br>
                                <button class="btn btn-danger" data-id="${rowData.enrollment_id}" id="btn_disapprove_enrollment">Decline</button>
                            `
                    }
                    return 'Approved'
                }
            }
        ],
    })

    $(document).on('click', '#btn_approve_enrollment', function (e) {
        e.preventDefault()

        const enrollment_id = $(this).data('id')
        $.ajax({
            type: "post",
            url: routes["enrollment"].url,
            data: {
                action: 'approve_enrollment',
                enrollment_id: enrollment_id
            },
            dataType: "json",
            success: function (response) {
                if (!response.success) {
                    if (response.error["INSERT_FAILED"]) {
                        Swal.fire({
                            title: "Approve Failed",
                            text: response.error["INSERT_FAILED"],
                            icon: "error"
                        });
                    }
                } else {
                    Swal.fire({
                        title: "Approve Success",
                        icon: "success",
                    });
                    apiGetEnrollmentApplications()
                }
            },
            fail: function () {
                Swal.fire({
                    title: "Approve Failed",
                    icon: "error"
                });
            }
        });
    });
    $(document).on('click', '#btn_disapprove_enrollment', function (e) {
        e.preventDefault()

        const enrollment_id = $(this).data('id')
        $.ajax({
            type: "post",
            url: routes["enrollment"].url,
            data: {
                action: 'decline_enrollment',
                enrollment_id: enrollment_id
            },
            dataType: "json",
            success: function (response) {
                if (!response.success) {
                    if (response.error["INSERT_FAILED"]) {
                        Swal.fire({
                            title: "Disapprove Failed",
                            text: response.error["INSERT_FAILED"],
                            icon: "error"
                        });
                    }
                } else {
                    Swal.fire({
                        title: "Application Disapproved",
                        icon: "success",
                    });
                    apiGetEnrollmentApplications()
                }
            },
            fail: function () {
                Swal.fire({
                    title: "Disapprove Failed",
                    icon: "error"
                });
            }
        });
    });
}
function apiGetEnrollmentApplications() {
    $.ajax({
        type: "get",
        url: "",
        data: {
            action: 'enrollments'
        },
        dataType: "json",
        success: function (response) {
            loadEnrollmentApplicationTable(response)
        },
        error: function () {
            tableEnrollmentApplications.clear().draw()
        }
    });
}

function loadEnrollmentApplicationTable(courses) {
    if (!courses || !courses.success) {
        tableEnrollmentApplications.clear().draw()
        return
    }
    let data = courses.data
    if (!Array.isArray(data) && typeof data === "object" && data !== null) {
        data = Object.values(data)
    }

    tableEnrollmentApplications.clear()
    tableEnrollmentApplications.rows.add(data)
    tableEnrollmentApplications.draw()
}
