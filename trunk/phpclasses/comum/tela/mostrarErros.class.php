<?php
    class mostrarErros
    {
        var $erros=array();
        var $alinhamentoErro;
        var $largura;

        function mostrarErros( $erros )
        {
            $this->erros = $erros;
            $this->setaLargura('500');
            $this->setaAlinhamentoErro('left');
            $this->setaTitulo("Atenção");
        }

        function setErros(  $erros  )
        {
            $this->erros = $erros;
        }

        function mostrarErrosNoForm(&$form)
        {
            $form->AddTitleMessage("<BR><CENTER><FONT COLOR=\"RED\"><B>".$this->titulo."</B></FONT></CENTER>");
            if ( is_array($this->erros) ) {
                foreach($this->erros as $id => $msgErro) {
                    $form->addErrorMessage("<B><CENTER>".$msgErro."</CENTER></B>");
                }
            }
        }

        /**
         * Seta o titulo a ser exibido
         */
        function setaTitulo($titulo)
        {
            $this->titulo = $titulo;
        }

        /**
         * Seta o alinhamento do texto a ser exibido com os erros
         */
        function setaAlinhamentoErro($alinhamentoErro)
        {
            $this->alinhamentoErro = $alinhamentoErro;
        }

        /**
         * Seta a largura para exibição do quadro de erros
         */
        function setaLargura($largura)
        {
            $this->largura = $largura;
        }

        /**
         * Exibe os erros sem a utilização de um formulário.
         */
        function exibeErros()
        {
            if ( is_array($this->erros) ) {
                echo "<div align = 'center'>";
                echo "<p style='{text-align: center; color:#FF0000; width: ".$this->largura."px;}'>".$this->titulo."</p>";
                echo "<p style='{text-align: ".$this->alinhamentoErro."; color:#000000; width: ".$this->largura."px;}'>";
                foreach($this->erros as $id => $msgErro) {
                    echo "<br>".$msgErro;
                }
                echo "</p>";
                echo "</div>";
            }
        }
    }
?>