//REPLACE WITH YOUR PUBLIC KEY AVAILABLE IN: https://www.mercadopago.com/developers/panel
const url = $('#url').val();
const publickey = $('#publickey').val();
const productCost = $('#amount').val();
const cod_pago = $('#cod_pago').val();
const productDescription = $('#cod').val();
const mp = new MercadoPago(publickey, { locale: 'es-AR' });

function loadCardForm() {
    const cardForm = mp.cardForm({
        amount: productCost,
        autoMount: true,
        form: {
            id: "form-checkout",
            cardholderName: {
                id: "form-checkout__cardholderName",
                placeholder: "Titular de la Tarjeta",
            },
            cardholderEmail: {
                id: "form-checkout__cardholderEmail",
                placeholder: "E-mail",
            },
            cardNumber: {
                id: "form-checkout__cardNumber",
                placeholder: "Números de la Tarjeta",
            },
            cardExpirationMonth: {
                id: "form-checkout__cardExpirationMonth",
                placeholder: "MM",
            },
            cardExpirationYear: {
                id: "form-checkout__cardExpirationYear",
                placeholder: "YY",
            },
            securityCode: {
                id: "form-checkout__securityCode",
                placeholder: "Cod. de Seguridad",
            },
            installments: {
                id: "form-checkout__installments",
                placeholder: "Pagos",
            },
            identificationType: {
                id: "form-checkout__identificationType",
            },
            identificationNumber: {
                id: "form-checkout__identificationNumber",
                placeholder: "Número de Identificación",
            },
            issuer: {
                id: "form-checkout__issuer",
                placeholder: "Issuer",
            },
        },
        callbacks: {
            onFormMounted: error => {
                if (error)
                    return console.warn("Form Mounted handling error: ", error);
            },
            onSubmit: event => {
                event.preventDefault();
                const {
                    paymentMethodId,
                    issuerId,
                    token,
                    installments,
                    identificationNumber,
                    identificationType,
                } = cardForm.getCardFormData();

                $.ajax({
                    url: url + "/api/payments/mp2.php?cod=" + cod_pago,
                    type: "POST",
                    data: {
                        token,
                        issuerId,
                        paymentMethodId,
                        installments: Number(installments),
                        payer: {
                            identification: {
                                type: identificationType,
                                number: identificationNumber,
                            },
                        },
                    },
                    success: function(data) {
                        console.log(data);
                        data = JSON.parse(data);
                        var alert_ = (data.status == 'approved' || data.status == 'authorized' || data.status == 'in_process') ? 'success' : 'danger'
                        $("#message").addClass('alert alert-' + alert_);
                        switch (data.message) {
                            case "accredited":
                                var msg = "¡Listo! Se acreditó tu pago.";
                                window.location.replace(url + "/checkout/detail?collection_id=" + data.id + "&message=" + msg)
                                break;
                            case "pending_contingency":
                                var msg = "Estamos procesando tu pago.";
                                window.location.replace(url + "/checkout/detail?collection_id=" + data.id + "&message=" + msg)
                                break;
                            case "pending_review_manual":
                                var msg = "Estamos procesando tu pago.";
                                window.location.replace(url + "/checkout/detail?collection_id=" + data.id + "&message=" + msg)
                                break;
                            case "cc_rejected_bad_filled_card_number":
                                var msg = "Revisa el número de tarjeta.";
                                break;
                            case "cc_rejected_bad_filled_date":
                                var msg = "Revisa la fecha de vencimiento.";
                                break;
                            case "cc_rejected_bad_filled_other":
                                var msg = "Revisa los datos.";
                                break;
                            case "cc_rejected_bad_filled_security_code":
                                var msg = "Revisa el código de seguridad de la tarjeta.";
                                break;
                            case "cc_rejected_blacklist":
                                var msg = "No pudimos procesar tu pago.";
                                break;
                            case "cc_rejected_call_for_authorize":
                                var msg = "Debes autorizar ante payment_method_id el pago de amount.";
                                break;
                            case "cc_rejected_card_disabled":
                                var msg = "Llama a payment_method_id para activar tu tarjeta o usa otro medio de pago.";
                                break;
                            case "cc_rejected_card_error":
                                var msg = "No pudimos procesar tu pago.";
                                break;
                            case "cc_rejected_duplicated_payment":
                                var msg = "Ya hiciste un pago por ese valor.";
                                break;
                            case "cc_rejected_high_risk":
                                var msg = "Tu pago fue rechazado.";
                                break;
                            case "cc_rejected_insufficient_amount":
                                var msg = "Tu payment_method_id no tiene fondos suficientes.";
                                break;
                            case "cc_rejected_invalid_installments":
                                var msg = "payment_method_id no procesa pagos en installments cuotas.";
                                break;
                            case "cc_rejected_max_attempts":
                                var msg = "Llegaste al límite de intentos permitidos.";
                                break;
                            case "cc_rejected_other_reason":
                                var msg = "No procesó el pago.";
                                break;
                        }
                        $("#message").html(msg)
                    }
                });
            }
        },
    });
};

//Handle transitions

setTimeout(() => {
    loadCardForm();
}, 100);