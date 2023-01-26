<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    $(document).ready(function() {
        Swal.fire({
            title: 'Введите пароль',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: false,
            confirmButtonText: 'ok',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            preConfirm: (pass) => {
                return fetch('/cp/admin_auth/' + '?pass=' + pass)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        window.location.reload();
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Или пароль пустой или не совпадает`
                        )
                    });
            }
        }).then((result) => {
            if (result.ok) {
                window.location.reload();
            }
        })
    });
</script>
