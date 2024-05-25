$(document).ready(function() {
    function updatePreview() {
        var money = initialMoney;
        var nuclear_missiles = initialNuclearMissiles;

        if ($('#invest_in_ecology').is(':checked')) {
            money -= 50;
        }

        $('input[name="build_shield[]"]:checked').each(function() {
            money -= 300;
        });

        $('input[name="improve_city[]"]:checked').each(function() {
            money -= 300;
        });

        if ($('#build_nuclear_technology').is(':checked')) {
            money -= 450;
        }

        var build_nuclear_missile = parseInt($('#build_nuclear_missile').val());
        if (build_nuclear_missile) {
            money -= build_nuclear_missile * 150;
        }

        var loan_amount = parseInt($('#loan_amount').val()) || 0;
        money += loan_amount;

        $('input[name^="launch_missiles"]').each(function() {
            $(this).prop('disabled', false);
        });

        $('input[name^="launch_missiles"]:checked').each(function() {
            nuclear_missiles--;
        });

        if (nuclear_missiles < 0) {
            nuclear_missiles = 0;
        }

        $('input[name^="launch_missiles"]').each(function() {
            if (!$(this).is(':checked') && nuclear_missiles <= 0) {
                $(this).prop('disabled', true);
            }
        });

        $('#money').text(money);
        $('#nuclear_missiles').text(nuclear_missiles);

        $('#readyButton').prop('disabled', money < 0);

        if (money < 0) {
            $('input[type="checkbox"]:checked').each(function() {
                if (!$(this).data('initiallyChecked')) {
                    $(this).prop('checked', false);
                }
            });
            updatePreview();
        }
    }

    function disableDestroyedCities() {
        $('input[name^="launch_missiles"]').each(function() {
            var targetCountry = $(this).attr('name').match(/\[(.*?)\]/)[1];
            var targetCityIndex = parseInt($(this).val());
            var isCityAlive = gameData.countries.find(country => country.name === targetCountry).cities[targetCityIndex].alive;

            if (!isCityAlive) {
                $(this).prop('disabled', true);
            }
        });
    }

    $('input, select').on('change', updatePreview);
    $('input[type="checkbox"]').each(function() {
        $(this).data('initiallyChecked', $(this).is(':checked'));
    });
    updatePreview();
    disableDestroyedCities();
});

// Подключаем необходимые библиотеки
function loadScripts() {
    var scripts = [
        "https://code.jquery.com/jquery-3.5.1.slim.min.js",
        "https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js",
        "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
    ];
    for (var i = 0; i < scripts.length; i++) {
        var script = document.createElement('script');
        script.src = scripts[i];
        document.head.appendChild(script);
    }
}

// Загружаем скрипты при загрузке страницы
document.addEventListener("DOMContentLoaded", function() {
    loadScripts();
});
