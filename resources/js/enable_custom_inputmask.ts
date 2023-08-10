// TODO: Refactor this code to use Vite and the new approach to import JS files
import Inputmask from "inputmask";

document.addEventListener('DOMContentLoaded', () => {
    const inputElement = document.getElementById("inputValue1");

    if (inputElement instanceof HTMLElement) {
        const maskOptions = {
            alias: "decimal",
            groupSeparator: ".",
            radixPoint: ",",
            digits: 2,
            digitsOptional: false,
            prefix: "R$ ",
            placeholder: "0",
            removeMaskOnSubmit: true,
            onUnMask: function (maskedValue: any, unmaskedValue: string) {
                const value = maskedValue.replace("R$ ", "")
                    .replace(".", "")
                    .replace(",", "");
                return (value);
            }
        };

        Inputmask(maskOptions).mask(inputElement);
    }
});
