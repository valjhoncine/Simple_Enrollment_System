let tableSubjects
$(document).ready(function () {
    initializeSubjectsTable()
    apiGetSubjects()
});
function initializeSubjectsTable() {
    tableSubjects = $("#tableSubjects").DataTable({
        processing: true,
        scrollX: true,
        columns: [
            {
                data: null, render: function (d, t, r, meta) {
                    return meta.row + 1
                }
            },
            { data: 'id', title: 'ID', visible: false },
            { data: 'code', title: 'Subject Code', },
            { data: 'name', title: 'Subject Title' },
            { data: 'course', title: 'Program/Course' },
            {
                data: null,
                title: 'Action',
                orderable: false,
                searchable: false,
                render: function (rowData) {
                    return `<a class="btn btn-info" href="${routes["subjects-edit"].url}?id=${rowData.id}">Edit</a>`
                }
            }
        ],
    })
}
function apiGetSubjects() {
    $.ajax({
        type: "get",
        url: "",
        data: {
            action: 'subjects'
        },
        dataType: "json",
        success: function (response) {
            loadCoursesTable(response)
        },
        error: function () {
            tableSubjects.clear().draw()
        }
    });
}

function loadCoursesTable(courses) {
    if (!courses || !courses.success) {
        tableSubjects.clear().draw()
        return
    }
    let data = courses.data

    if (!Array.isArray(data) && typeof data === "object" && data !== null) {
        data = Object.values(data)
    }

    tableSubjects.clear()
    tableSubjects.rows.add(data)
    tableSubjects.draw()
}
