<?php
    /* lm = lateral_menu*/

    // Return the html code of the lateral menu
    function show_lateral_menu(): string
    {
        $lm_links = array(
            array("icon_url" => "https://img.icons8.com/ios/344/link--v1.png", "link_url" => "login.php", "link_text" => "Login")
        );

        $lm_html_links = '';
        foreach($lm_links as $lm_link){
            $lm_html_links .= add_link($lm_link["icon_url"], $lm_link["link_url"], $lm_link["link_text"]);
        }

        $html_code = '
                    <div class="lateral_menu">
                        <h1 class="lm_title">Menu title</h1>
                        ' . $lm_html_links . '
                    </div>
                    ';

        return $html_code;
    }

    // Return the html code of a link in the lateral menu
    function add_link(string $icon_url, string $link_url, string $link_text): string
    {
        $img_html_code = '
                        <img class="lm_link_img" src="' . $icon_url . '" alt="' . $link_text . '_icon">
                        ';
        $link_text = (!empty($icon_url)) ? $img_html_code . $link_text : $link_text;
        $html_code = '
                    <a class="lm_link" href="' . $link_url . '">' . $link_text . '</a>
                    ';

        return $html_code;
    }
?>