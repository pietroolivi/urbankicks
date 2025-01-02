document.addEventListener('DOMContentLoaded', () => {
    const otpEle = document.getElementById('otp');
    const inputs = [...otpEle.querySelectorAll('.otp-input')];

    /* ***************************************************************************************** */
    /* We want to make sure that only digits are entered into our OTP input fields.              */
    /* To make this happen, we first select all of the input fields in the OTP container         */ 
    /* using querySelectorAll('.otp__input'). Then, we convert the NodeList returned by          */
    /* querySelectorAll() into an array using Array.from() so that we can use array methods      */
    /* like forEach(). Next, we need to handle the keydown event for each input. This event      */
    /* is triggered every time a key is pressed within an input field. Inside the handler,       */
    /* we check if the key pressed is a digit using the /^[0-9]{1}$/ regular expression pattern. */
    /* If it's not a digit, we prevent its default behavior by calling e.preventDefault().       */
    /* It's worth noting that we allow the use of the Backspace and Delete keys to remove the    */
    /* current digit, just as you would expect. So, we also compare the pressed key with them.   */
    /* ***************************************************************************************** */
    const handleKeyDown = (e) => {
        const { target } = e;
        const currentValue = target.value;
        const index = inputs.indexOf(target);
        switch (e.key) {
            case 'ArrowDown':
                target.value = (currentValue === '' || !/^[0-9]{1}$/.test(currentValue))
                                ? 0
                                : Math.max(parseInt(currentValue, 10) - 1, 0);
                break;
            case 'ArrowUp':
                target.value = (currentValue === '' || !/^[0-9]{1}$/.test(currentValue))
                                ? 0
                                : Math.min(parseInt(currentValue, 10) + 1, 9);
                break;
            default:
                if (!/^[0-9]{1}$/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete') {
                    e.preventDefault();
                }
                break;
        }
    };

    /* ****************************************************************************************************** */
    /* Our first improvement will be to automatically focus on the next field when the user enters a          */
    /* digit or presses the right arrow key. This way, users can quickly enter the entire OTP without         */
    /* having to manually switch between fields. To do this, we'll handle the keyup event on each input       */
    /* field in the OTP container. This event is triggered every time a key is released within an input       */
    /* field. In the event handler, we'll check if the pressed key is a digit or the right arrow key.         */
    /* If it is, we'll jump to the next field. We'll only do this if we haven't reached the last input        */
    /* field yet, which we'll determine by comparing the current index with the total number of input fields. */
    /* ****************************************************************************************************** */
    const handleKeyUp = (e) => {
        const index = inputs.indexOf(e.target);
        switch (e.key) {
            case '0':
            case '1':
            case '2':
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
            case 'ArrowRight':
                if (index < inputs.length - 1) {
                    // Jump to the next field
                    inputs[index + 1].focus();
                }
                break;
            /* Similarly, we want to bring the user back to the previous field when they press the left arrow or     */
            /* Backspace key. Just make sure to check if we aren't on the first field by comparing the index with 0. */
            case 'ArrowLeft':
            case 'Backspace':
                if (index > 0) {
                    // Jump to the previous field
                    inputs[index - 1].focus();
                }
                break;
            case 'Home':
                // Jump to the first field
                inputs[0].focus();
                break;
            case 'End':
                // Jump to the first field
                inputs[inputs.length - 1].focus();
                break;
            default:
                break;
        }
    };

    /* ******************************************************************************************** */
    /* When a user clicks on an input field within the OTP container, it becomes active. To make    */
    /* it easier for users to replace the current digit in the input field, we can use the focus    */
    /* event to automatically select the input field's content when it is focused. We do this by    */
    /* using the select() method of the target element inside the event handler. This selects the   */
    /* current digit in the input field, so users can replace it without having to delete it first. */
    /* ******************************************************************************************** */
    const handleFocus = (e) => {
        e.target.select();
    };

    /* ************************************************************************************** */
    /* It's common for users to copy a number and then paste it into a field. To handle this  */
    /* situation, we can use the paste event to capture when a user pastes text into an input */
    /* field. Then, we split the value of the first input field into an array of digits and   */
    /* set the value of each remaining input field to the corresponding digit in the array.   */
    /* ************************************************************************************** */
    const handlePaste = (e) => {
        e.preventDefault();
        const pastedText = e.clipboardData.getData('text');
        if (!/^[0-9]{4}$/.test(pastedText)) {
            return;
        }
        const digits = pastedText.split('');
        inputs.forEach((input, index) => input.value = digits[index]);
        inputs[inputs.length - 1].focus();
    };

    inputs.forEach((input) => {
        input.addEventListener('keydown', handleKeyDown);
        input.addEventListener('keyup', handleKeyUp);
        input.addEventListener('focus', handleFocus);
        input.addEventListener('paste', handlePaste);
    });
});