document.getElementById('contactForm').addEventListener('submit', async function (e) {
    e.preventDefault(); // Evita la recarga de la página.
  
    const form = e.target;
    const formData = new FormData(form);
  
    try {
      const response = await fetch('send_mail.php', {
        method: 'POST',
        body: formData,
      });
  
      const result = await response.json();
      const messageElement = document.getElementById('formResponse');
      if (result.success) {
        messageElement.textContent = result.message;
        messageElement.style.color = 'green';
        form.reset(); // Limpia el formulario.
      } else {
        messageElement.textContent = result.message;
        messageElement.style.color = 'red';
      }
    } catch (error) {
      document.getElementById('formResponse').textContent = 'Error al enviar el formulario.';
      document.getElementById('formResponse').style.color = 'red';
    }
  });
  