let tableCourses
$(document).ready(function () {
    initializeCoursesTable()
    apiGetCourses()
});
function initializeCoursesTable() {
    tableCourses = $("#tableCourses").DataTable({
        processing: true,
        scrollX: true,
        columns: [
            {
                data: null, render: function (d, t, r, meta) {
                    return meta.row + 1
                }
            },
            { data: 'id', title: 'ID', visible: false },
            { data: 'code', title: 'Course Code', },
            { data: 'name', title: 'Course Title' },
            {
                data: null,
                title: 'Action',
                orderable: false,
                searchable: false,
                render: function (rowData) {
                    if (rowData.id > 1) {
                        return `<a class="btn btn-info" href="${routes["courses-edit"].url}?id=${rowData.id}">Edit</a>`
                    }
                    return ''
                }
            }
        ],
    })
}
function apiGetCourses() {
    $.ajax({
        type: "get",
        url: "",
        data: {
            action: 'courses'
        },
        dataType: "json",
        success: function (response) {
            loadCoursesTable(response)
        },
        error: function () {
            tableCourses.clear().draw()
        }
    });
}

function loadCoursesTable(courses) {
    if (!courses || !courses.success) {
        tableCourses.clear().draw()
        return
    }
    let data = courses.data
    if (!Array.isArray(data) && typeof data === "object" && data !== null) {
        data = Object.values(data)
    }

    tableCourses.clear()
    tableCourses.rows.add(data)
    tableCourses.draw()
}
