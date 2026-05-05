document.addEventListener('DOMContentLoaded', function () {
    const penyewaanIdInput = document.getElementById('penyewaan_id');
    const tanggalPengembalianInput = document.getElementById('tanggal_pengembalian');
    const dendaInput = document.getElementById('denda');

    // Fungsi untuk menghitung denda
    function hitungDenda() {
        const penyewaanId = penyewaanIdInput.value;
        const tanggalPengembalian = tanggalPengembalianInput.value;

        if (penyewaanId && tanggalPengembalian) {
            fetch(`/pengembalians/${penyewaanId}/hitung-denda`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    tanggal_pengembalian: tanggalPengembalian
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        dendaInput.value = data.denda;
                    } else {
                        alert(data.message || 'Terjadi kesalahan saat menghitung denda.');
                        dendaInput.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal menghitung denda. Silakan coba lagi.');
                });
        }
    }

    // Event listener untuk input penyewaan ID dan tanggal pengembalian
    penyewaanIdInput.addEventListener('change', hitungDenda);
    tanggalPengembalianInput.addEventListener('change', hitungDenda);
});