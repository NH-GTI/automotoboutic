function onRecaptchaSuccess() {
    // This function is called when user successfully completes the captcha
    console.log("Sucessfully completed captcha");

    // Enable your submit button
    document.getElementById("submitMessage").disabled = false;
}
