var allow_submit = false

function captcha_filled () {
    allow_submit = true
}

function captcha_expired () {
    allow_submit = false
}

function check_captcha_filled (e) {
    console.log('verify-captcha')
    if (!allow_submit) {
        alert('Please fill out the Captcha')
        return false
    }
    captcha_expired()
    return true
}