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
            {
                data: null,
                title: 'Status',
                render: function (rowData) {
                    return `
                        <div class="badge bg-${rowData.status === 'Active' ? 'primary' : 'danger'} text-white rounded-pill">${rowData.status}</div>
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
