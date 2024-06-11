// Barra de imagenes de productos.
let currentSlide = 0;

function moveSlide(n) {
    const slides = document.querySelectorAll('.slide');
    currentSlide = (currentSlide + n + slides.length) % slides.length;
    if (currentSlide < 0) {
        currentSlide = slides.length - 1; // Para volver al ultimo slide si llega al primer slide
    }
    document.querySelector('.slide-container').style.transform = `translateX(-${currentSlide * 100}%)`;
}

function uploadImages() {
    const fileInput = document.getElementById('image-upload');
    const userName = document.getElementById('user-name').value;
    const userEmail = document.getElementById('user-email').value;
    const userPhone = document.getElementById('user-phone').value;
    const files = fileInput.files;

    const formData = new FormData();
    formData.append('name', userName);
    formData.append('email', userEmail);
    formData.append('phone', userPhone);
    for (let file of files) {
        formData.append('images', file);
    }

    fetch('contactoEmpresa.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            alert('Informacion enviada correctamente');
            document.getElementById('user-name').value = '';
            document.getElementById('user-email').value = '';
            document.getElementById('user-phone').value = '';
            fileInput.value = '';
            return true;
        } else {
            alert('Ha ocurrido un error al enviar la informacion');
            return false;
        }
    })
    .catch(error => {
        console.error('Error al enviar la informacion:', error);
        return false;
    });

    return false; // Evita que el formulario se envie de forma predeterminada
}
