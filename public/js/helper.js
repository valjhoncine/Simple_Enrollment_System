function setProgramCourse(code, name) {
    return `${code} - ${name}`
}
function showFormValidationErrors(id, errors) {
    const inputField = $(`#${id}`);
    const errorContainer = $(`#${id}Error`);

    inputField.addClass("is-invalid");
    errorContainer.html("");

    errors.forEach(e => {
        errorContainer.append(e + "<br>");
    });
}
function removeFormValidationErrors(id, className){
    $(`#${id}`).removeClass(className);
    $(`#${id}Error`).text("");
}
