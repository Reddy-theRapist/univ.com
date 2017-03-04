var done = false;
var player;

function onYouTubeIframeAPIReady() {
   player = new YT.Player('hiddenCharacter',
   {
    events: {
      'onReady': onPlayerReady,
      'onStateChange': onPlayerStateChange
    }
  });
}

function onPlayerReady(event) {
  event.target.playVideo();
}
function onPlayerStateChange(event) {
  }


  //  player.stopVideo();
  //  document.getElementById('hiddenCharacter').contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');


  //   player.playVideo();
  //   document.getElementById('hiddenCharacter').contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
