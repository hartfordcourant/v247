getScript('http://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.0/jquery.min.js', start);

/* Asynchronous code written by the NGUX development team */
function getScript(src, callback) {
  var s = document.createElement('script');
  s.async = true;
  if (callback) {
    if (s.addEventListener) {
      s.addEventListener('load', callback, false);
    } else if (s.attachEvent) {
      s.attachEvent('onreadystatechange', function(e) {
        var t = e.srcElement;
        if (/loaded|complete/.test(t.readyState)) {
          callback();
        }
      });
    }
  }
  s.src = src;
  (document.body || document.getElementsByTagName('body')[0] || document.documentElement).appendChild(s);
}
function start() 
{
    /*$leagues = $('#leagues');
    $('#leagues').remove();
    $('#hed_dropdown').append($leagues);*/
    
    var hold = new Array();
    var cards = document.getElementsByClassName('score_card');
    console.log(cards);
        //cards[(cards.length)-1].remove();
    var num_cards = cards.length;

        for ( var i = 0; i < cards.length; i++ )
        {
            hold.push(cards[i]);
        }
        
    document.getElementsByTagName('select')[0].onchange =
    function()
    {   
        $('.score_card').remove();
        var val = this.value;
        
        for ( var j = 0; j < num_cards; j++ )
        {
            if ( (hold[j].className == 'score_card ' + val) || val == 'all' )
            {   
                document.getElementById('barker').appendChild( hold[j] );
            }
        }
    };
    
    /* carousel effect based on tutorial from: http://web.enavu.com/tutorials/making-an-infinite-jquery-carousel/ */
    $('#right').click(
        function(e)
        {
            e.preventDefault();
        
            var full_card_width = getCardWidth();
            var left_shift = parseInt( $('#barker').css('left') ) - full_card_width;
        
            $('#barker').animate(
                {
                    'left' : left_shift
                },  function()
                    {
                        $('#barker .score_card:last').after( $('#barker .score_card:first') );
                    
                         $('#barker').css(
                            {
                                'left' : '0'
                            }
                        );
                    }   
            );
        }
    );

    $('#left').click(
        function(e)
        {
            e.preventDefault();
        
            var full_card_width = getCardWidth();
            var left_shift = parseInt($('#barker').css('left')) + full_card_width;
        
            $('#barker').animate(
                {
                    'left' : left_shift
                },  function()
                    {
                        $('#barker .score_card:first').before( $('#barker .score_card:last') );
                    
                         $('#barker').css(
                            {
                                'left' : '0'
                            }
                        );
                    }   
            );
        }
    );
    
    function getCardWidth()
    {   
        var card_left_margin = parseInt( $('.score_card').css('margin-left') );
        var card_width = $('.score_card').outerWidth();
        var full_card_width = card_left_margin + card_width;

        return full_card_width;
    }
    
    
}