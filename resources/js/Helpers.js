export function checkValidateStatus(error, form = {}) {
    if (error) {
        return 'error';
    }

    if (form.processing) {
        return 'validating';
    }

    return null;
}
