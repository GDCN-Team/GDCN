export function checkValidateStatus(error, form = {}) {
    if (error) {
        return 'error';
    }

    if (form.processing) {
        return 'validating';
    }

    return null;
}

export function back() {
    history.back();
}

export function formatTime(time) {
    if (!time) {
        return '无';
    }

    const date = new Date(time);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
}
