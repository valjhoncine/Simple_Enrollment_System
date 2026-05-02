let tableUsers
$(document).ready(function () {
    initializeUsersTable()
    apiGetUsers();
});
function initializeUsersTable() {
    tableUsers = $("#tableUsers").DataTable({
        processing: true,
        scrollX: true,
        columns: [
            {
                data: null, render: function (d, t, r, meta) {
                    return meta.row + 1
                }
            },
            { data: 'id', title: 'ID', visible: false },
            { data: 'first_name', title: 'First Name', },
            { data: 'last_name', title: 'Last Name' },
            { data: 'email', title: 'Email' },
            { data: 'role', title: 'Role' },
            { data: 'course', title: 'Program/Course' },
            { data: 'status', title: 'Status' },
            {
                data: null,
                title: 'Action',
                orderable: false,
                searchable: false,
                render: function (rowData) {
                    return `
                        <button class="btn btn-info" data-id="${rowData.id}">
                            <i class="fa fa-edit"></i>
                        </button>
                    `;
                }
            }
        ],
    })
}
function apiGetUsers() {
    $.ajax({
        type: "get",
        url: "",
        data: {
            action: 'users'
        },
        dataType: "json",
        success: function (response) {
            loadUsersTable(response)
        },
        error: function () {
            tableUsers.clear().draw()
        }
    });
}
function loadUsersTable(users) {
    if (!users || !users.success) {
        tableUsers.clear().draw()
        return
    }
    tableUsers.clear()
    tableUsers.rows.add(users.data)
    tableUsers.draw()
}
