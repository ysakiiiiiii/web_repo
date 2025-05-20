$(document).ready(function () {
  const $searchBtn = $('#search-btn');
  const $inputField = $('#name-input');
  const $nameScreen = $('#name-screen');
  const $imageScreen = $('#main-screen');
  const $mainImage = $('#main-screen-image'); 
  const $aboutScreen = $('#about-screen');
  const $typeScreen = $('#type-screen');
  const $idScreen = $('#id-screen');
  const $rightButton = $('.right-nav-button');
  const $leftButton = $('.left-nav-button');

  let current = 1;

  function getPokemonData(pokemon) {
    if (!pokemon) return;
    $.ajax({
      url: `https://pokeapi.co/api/v2/pokemon/${pokemon}`,
      method: 'GET',
      dataType: 'json',
      success: function (data) {
        if (!data.id) return;
        current = data.id;
        let id = ('00' + data.id).slice(-3);
        $mainImage.css('background-image', `url('https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/showdown/shiny/${data.id}.gif')`);
         $mainImage
         .animate({ top: -50 }, 100)
         .animate({ top: 0 }, 100)
         .animate({ top: -20 }, 100)
         .animate({ top: 0 }, 100)
         .animate({ top: -10 }, 100)
         .animate({ top: 0 }, 100);

        $nameScreen.text(data.name);
        $typeScreen.text(data.types[0].type.name);
        $idScreen.text(`#${data.id}`);
        $aboutScreen.text(`Height: ${data.height * 10}cm Weight: ${data.weight / 10}kg Ability: ${data.abilities.map(a => a.ability.name).join(', ')}`);
        $inputField.val('');
      }
    });
  }

  $inputField.on('keydown', function (event) {
    if (event.key === 'Enter') $searchBtn.click();
  });
  $searchBtn.on('click', function () {
    getPokemonData($inputField.val());
  });
  $rightButton.on('click', function () {
    getPokemonData(current + 1);
  });
  $leftButton.on('click', function () {
    if (current <= 1) return;
    getPokemonData(current - 1);
  });
  $(document).on('keydown', function (event) {
    if (event.key === 'ArrowRight') {
      $rightButton.click();
    } else if (event.key === 'ArrowLeft') {
      $leftButton.click();
    }
  });
});