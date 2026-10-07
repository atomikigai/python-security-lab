const Adyen = require('./Adyen');
const { getCurrentTimestamp } = require('./utils/helper');



function encryptCardData(
    card, 
    month, 
    year, 
    cvc,
    adyenKey,
    stripeKey,
) {
    if (!card || !month || !year || !cvc) {
        throw new Error('Missing card details');
    }
    if (!adyenKey) {
        throw new Error('Missing Adyen key');
    }
    if (!stripeKey) {
        stripeKey = "REDACTED_CREDENTIAL";
    }

    // Convert card from REDACTED_NUMERIC_IDENTIFIER to 4242 4242 4242 4242
    const cardNumber = card.replace(/(.{4})/g, '$1 ').trim();
    
    const cardDetail = {
        encryptedCardNumber: {"number":cardNumber,"generationtime":getCurrentTimestamp(),"numberBind":"1","activate":"3","referrer":"https://example.invalid/legacy" + stripeKey +"/4.4.1/securedFields.html?type=card&d=aHR0cHM6Ly93d3cubXl0aGVyZXNhLmNvbQ==","numberFieldFocusCount":"3","numberFieldLog":"fo@44070,cl@44071,KN@44082,fo@44324,cl@44325,cl@44333,KN@44346,KN@44347,KN@44348,KN@44350,KN@44351,KN@44353,KN@44354,KN@44355,KN@44356,KN@44358,fo@44431,cl@44432,KN@44434,KN@44436,KN@44438,KN@44440,KN@44440","numberFieldClickCount":"4","numberFieldKeyCount":"REDACTED_CREDENTIAL"},
        encryptedExpiryMonth: {"expiryMonth":month,"generationtime":getCurrentTimestamp()},
        encryptedExpiryYear: {"expiryYear":year,"generationtime":getCurrentTimestamp()},
        encryptedSecurityCode: {"cvc":cvc,"generationtime":getCurrentTimestamp(),"cvcBind":"1","activate":"4","referrer":"https://example.invalid/legacy" + stripeKey +"/4.4.1/securedFields.html?type=card&d=aHR0cHM6Ly93d3cubXl0aGVyZXNhLmNvbQ==","cvcFieldFocusCount":"4","cvcFieldLog":"fo@122,cl@123,KN@136,KN@138,KN@140,fo@11204,cl@11205,ch@11221,bl@11221,fo@33384,bl@33384,fo@50318,cl@50319,cl@50321,KN@50334,KN@50336,KN@50336","cvcFieldClickCount":"4","cvcFieldKeyCount":"REDACTED_CREDENTIAL","cvcFieldChangeCount":"1","cvcFieldBlurCount":"2","deactivate":"2"}
    }
    
    const obj = new Adyen(adyenKey)
    obj.generateKey()
    
    for (const [key, value] of Object.entries(cardDetail)) {
        cardDetail[key] = obj.encryptData(value);
    }
    return cardDetail;

}

module.exports = encryptCardData;