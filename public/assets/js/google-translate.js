// document.getElementById("languageSwitcher").addEventListener("change", function () {
//     var lang = this.value;

//     triggerGoogleTranslate(lang);
// });

// function triggerGoogleTranslate(lang) {
//     var translateDropdown = document.querySelector(".goog-te-combo");

//     if (translateDropdown) {
//         translateDropdown.value = lang;
//         translateDropdown.dispatchEvent(new Event("change"));
//         console.log("Selected language:", translateDropdown.value);

//     }
// }
// Hide ugly default Google Translate toolbar

const customTranslatedWords = {
    "Save": [{ "en": "Save", "de": "Spare" }],
}
function hideGoogleToolbar() {
    let style = document.createElement('style');
    style.innerHTML = `
      .goog-te-banner-frame.skiptranslate, 
      .goog-te-gadget-icon,
      .goog-te-menu-value,
      #goog-gt-tt, 
      .goog-te-balloon-frame {
        display: none !important;
      }
      body {
        top: 0 !important;
      }
    `;
    document.head.appendChild(style);
}

document.addEventListener("DOMContentLoaded", function () {
    // hideGoogleToolbar();
    // document.getElementById("languageSwitcher").value = "de";
    // autoTranslateToGerman();
});

function googleTranslateElementInit() {
    new google.translate.TranslateElement({ includedLanguages: 'en,de', }, 'google_translate_element');
}


// function autoTranslateToGerman() {


//     const select = document.querySelector('#languageSwitcher');
//     if (select) {
//         console.log("Auto translating to German...", select);

//         select.value = 'de';
//         select.dispatchEvent(new Event('change'));
//     }

// }



$(document).ready(function () {
    // Init Select2
    $('#languageSwitcher').select2({
        templateResult: formatState,
        templateSelection: formatState,
        minimumResultsForSearch: -1
    });

    function formatState(state) {
        if (!state.id) return state.text;
        const image = $(state.element).data('image');
        return $('<span><img src="' + image + '" class="me-2" width="20"/> ' + state.text + '</span>');
    }

    // Auto-switch to German on load
    const interval = setInterval(function () {
        const select = document.querySelectorAll('.goog-te-combo')[0];
        if (select && select.options.length > 0) {
            //first appearing option            
            const firstOption = select.options[1].value;

            select.dispatchEvent(new Event('change', { bubbles: true }));
            $("#languageSwitcher").val(firstOption).trigger("change");
            console.log("lang ", firstOption);

            clearInterval(interval);
        }
    }, 100);

    // On language change
    $('#languageSwitcher').on('change', function () {
        const lang = $(this).val();
        const gTranslateSelect = document.querySelector('.goog-te-combo');
        if (gTranslateSelect) {
            gTranslateSelect.value = lang;
            gTranslateSelect.dispatchEvent(new Event('change', { bubbles: true }));
        }

        applyCustomTranslations(lang);
    });

    function applyCustomTranslations(lang) {
        let allCustomTranslateElements = document.querySelectorAll('.custom-translate');
        allCustomTranslateElements.forEach(function (element) {
            const customWords = customTranslatedWords[element.getAttribute("data-word")];
            console.log("Custom words: ", customWords);
            if (customWords) {
                const word = customWords.find(item => item[lang]);
                if (word) {
                    const translatedWord = word[lang];
                    console.log("Translated word: ", translatedWord);
                    // Apply the translation to the element
                    element.textContent = translatedWord;
                }
            }
        });

    }


    //  $('.goog-te-combo').on('change', function () {
    //     const lang = $(this).val();
    //     const translateSelectCustom = document.querySelector('#languageSwitcher');
    //         console.log("Google Translate changed to: ", lang);

    //     if (translateSelectCustom) {

    //         translateSelectCustom.value = lang;
    //         translateSelectCustom.dispatchEvent(new Event('change', { bubbles: true }));
    //     }
    // });


    // $("#languageSwitcher").val($(".goog-te-combo").val()).trigger("change");
});
