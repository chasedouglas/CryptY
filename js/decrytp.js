// decrypt.js
function DeCryptX(encrypted_email) {
    var decrypted_email = atob(encrypted_email);
    window.location.href = 'mailto:' + decrypted_email;
}
