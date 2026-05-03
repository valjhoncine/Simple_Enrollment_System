let tableSchedules
$(document).ready(function () {
    initializeSchedulesTable()
    apiGetSchedules()
});
function initializeSchedulesTable() {
    tableSchedules = $("#tableSchedules").DataTable({
        processing: true,
        scrollX: true,
        columns: [
            {
                data: null, render: function (d, t, r, meta) {
                    return meta.row + 1
                }
            },
            { data: 'id', title: 'ID', visible: false },
            { data: 'day', title: 'Day', },
            { data: 'time', title: 'Time' },
            { data: 'subject', title: 'Subject' },
            { data: 'course', title: 'Program/Course' },
            {
                data: null,
                title: 'Action',
                orderable: false,
                searchable: false,
                render: function (rowData) {
                    return `<a class="btn btn-info" href="${routes["schedules-edit"].url}?id=${rowData.id}">Edit</a>`
                }
            }
        ],
    })
}
function apiGetSchedules() {
    $.ajax({
        type: "get",
        url: "",
        data: {
            action: 'schedules'
        },
        dataType: "json",
        success: function (response) {
            loadSchedulesTable(response)
        },
        error: function () {
            tableSchedules.clear().draw()
        }
    });
}

function loadSchedulesTable(response) {
    if (!response || !response.success) {
        tableresponses.clear().draw()
        return
    }
    let data = response.data

    if (!Array.isArray(data) && typeof data === "object" && data !== null) {
        data = Object.values(data)
    }

    tableSchedules.clear()
    tableSchedules.rows.add(data)
    tableSchedules.draw()
}
