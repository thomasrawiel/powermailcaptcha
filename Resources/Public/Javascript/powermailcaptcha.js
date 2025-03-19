window.addEventListener("DOMContentLoaded", () => {
    let gr = document.querySelector('#g-recaptcha-response');
    let sitekey = document.querySelector('#powermail-gRecaptcha').getAttribute('data-sitekey');
    console.log(gr);
    console.log('sitekey',sitekey);
    let f = gr.form;
    f.addEventListener('submit', async e => {
        e.preventDefault();

        if (!f.checkValidity()) {
            f.reportValidity();
            return;
        }

        await grecaptcha.ready(async () => {
            const token = await grecaptcha.execute(sitekey, {action: 'submit'});

            document.getElementById('g-recaptcha-response').value = token;

            if (f.checkValidity()) {
                console.log( document.getElementById('g-recaptcha-response'));
                   f.submit();
            }
        });






    })
});