export function useFormatter() {
    function formatCurrency(value) {
        return new Intl.NumberFormat('en-PH', {style: 'currency', currency: 'PHP'}).format(value);
    }

    function formatNumber(value) {
        return new Intl.NumberFormat('en-PH').format(value);
    }

    function formatDate(value) {
        return value
            ? new Intl.DateTimeFormat('en-PH', {
                dateStyle: 'medium',
                timeZone: 'Asia/Manila',
            }).format(new Date(value))
            : '';
    }

    function formatDateTime(value) {
        return value
            ? new Intl.DateTimeFormat('en-PH', {
                dateStyle: 'medium',
                timeStyle: 'short',
                timeZone: 'Asia/Manila',
            }).format(new Date(value))
            : '';
    }

    return {
        formatCurrency,
        formatNumber,
        formatDate,
        formatDateTime,
    };
}
