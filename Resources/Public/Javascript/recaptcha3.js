window.addEventListener("DOMContentLoaded", () => {
    const gr = document.querySelector('#g-recaptcha-response');
    const captchaElement = document.querySelector('#powermail-gRecaptcha');
    const sitekey = captchaElement.dataset.sitekey;
    const action = captchaElement.dataset.action;
    let f = gr.form;
    f.addEventListener('submit', async e => {
        e.preventDefault();

        if (!f.checkValidity()) {
            f.reportValidity();
            return;
        }

        await grecaptcha.ready(async () => {
            const token = await grecaptcha.execute(sitekey, { action });

            document.getElementById('g-recaptcha-response').value = token;

            if (f.checkValidity()) {
                f.submit();
            }
        });
    })
});
