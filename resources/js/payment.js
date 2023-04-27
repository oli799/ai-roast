document.querySelector("button").disabled = true;

const stripe = Stripe(import.meta.env.VITE_STRIPE_KEY ?? "");
const elements = stripe.elements();
const card = elements.create("card", {
    style: {
        base: {
            fontSize: "16px",
        },
    },
});

const form = document.getElementById("payment-form");
const name = document.getElementById("name");

card.mount("#card-element");

card.on("change", function (event) {
    document.querySelector("button").disabled = event.empty;

    let displayError = document.getElementById("card-errors");

    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = "";
    }
});

form.addEventListener("submit", async function (event) {
    event.preventDefault();

    const { paymentMethod, error } = await stripe.createPaymentMethod({
        type: "card",
        card: card,
        billing_details: {
            name: name.value,
        },
    });

    if (error) {
        console.log(error);
    } else {
        let input = document.createElement("input");

        input.setAttribute("type", "hidden");
        input.setAttribute("name", "payment_method");
        input.setAttribute("value", paymentMethod.id);

        form.appendChild(input);
        form.submit();
    }
});
