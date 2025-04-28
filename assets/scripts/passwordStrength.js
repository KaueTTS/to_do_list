const passwordInput = document.getElementById('password');
const strengthContainer = document.getElementById('password-strength');
const strengthBar = document.getElementById('password-strength-bar');
const strengthText = document.getElementById('strength-text');

passwordInput.addEventListener('input', function() {
    const password = passwordInput.value;

    if (password.length > 0) {
        strengthContainer.style.display = 'block';
    } else {
        strengthContainer.style.display = 'none';
    }

    let strength = 0;

    if (password.length >= 8) strength += 1;
    if (password.match(/[a-z]/)) strength += 1;
    if (password.match(/[A-Z]/)) strength += 1;
    if (password.match(/[0-9]/)) strength += 1;
    if (password.match(/[^a-zA-Z0-9]/)) strength += 1; // Caracteres especiais

    // Atualiza a barra e o texto de acordo com a força
    switch(strength) {
        case 0:
            strengthBar.style.width = "0";
            strengthBar.style.backgroundColor = "red";
            strengthText.textContent = "";
            break;
        case 1:
            strengthBar.style.width = "20%";
            strengthBar.style.backgroundColor = "red";
            strengthText.textContent = "Senha muito fraca";
            strengthText.style.color = "red";
            break;
        case 2:
            strengthBar.style.width = "40%";
            strengthBar.style.backgroundColor = "orange";
            strengthText.textContent = "Senha fraca";
            strengthText.style.color = "orange";
            break;
        case 3:
            strengthBar.style.width = "60%";
            strengthBar.style.backgroundColor = "yellowgreen";
            strengthText.textContent = "Senha razoável";
            strengthText.style.color = "yellowgreen";
            break;
        case 4:
            strengthBar.style.width = "80%";
            strengthBar.style.backgroundColor = "green";
            strengthText.textContent = "Senha boa";
            strengthText.style.color = "green";
            break;
        case 5:
            strengthBar.style.width = "100%";
            strengthBar.style.backgroundColor = "darkgreen";
            strengthText.textContent = "Senha excelente";
            strengthText.style.color = "darkgreen";
            break;
    }
});