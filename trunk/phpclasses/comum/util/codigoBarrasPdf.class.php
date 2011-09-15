<?php
    require_once "comum/util/class_barcode.class.php";

    class codigoBarrasPdf extends barCodeI25
    {
        var $objPDF;
        var $coluna;
        var $linha;

        function codigoBarrasPdf(&$objPdf)
        {
            parent::barCodeI25();
            $this->objPDF = &$objPdf;
        }

        function desenhaCodigo($codigo,$coluna,$linha,$altura=50)
        {
            $this->codigo = trim($codigo);
            $this->coluna = $coluna;
            $this->linha = $linha;
            $this->altb = $altura;
            $this->ebf = 1;
            $this->ebg = 3;
			
			//define barcode patterns
			//0 - Estreita    1 - Larga
			//Dim bc(60) As String   Obj.DrawWidth = 1
			$this->bc[0] = "00110";          //0 digit
			$this->bc[1] = "10001";          //1 digit
			$this->bc[2] = "01001";          //2 digit
			$this->bc[3] = "11000";          //3 digit
			$this->bc[4] = "00101";          //4 digit
			$this->bc[5] = "10100";          //5 digit
			$this->bc[6] = "01100";          //6 digit
			$this->bc[7] = "00011";          //7 digit
			$this->bc[8] = "10010";          //8 digit
			$this->bc[9] = "01010";          //9 digit
			$this->bc[10] = "0000";          //pre-amble
			$this->bc[11] = "100";           //post-amble

            if (strlen($this->codigo)>0)
            {
                $th = "";
                $new_string = "";
                $lbc = 0; $xi = 0; $k = 0;
                $this->bc_string = $this->codigo;
				
                $this->bc_string = strtoupper($this->bc_string);
                $this->objPDF->setLineStyle(1);

                if ((strlen($this->bc_string) % 2) != 0)
                {
                    $this->bc_string = "0" . $this->bc_string;
                }

                $lbc = strlen($this->bc_string) - 1;
				
                //Gera o código com os patterns
                for ($xi=0; $xi<= $lbc; $xi++)
                {
                    $k = (int) substr($this->bc_string,$xi,1);
                    $new_string = $new_string . $this->bc[$k];
                }
				
                $this->bc_string = $new_string;
				

                //Faz a mixagem do Código
                $this->mixCode();

                $this->bc_string = $this->bc[10] . $this->bc_string .$this->bc[11];  //Adding Start and Stop Pattern

                $lbc = strlen($this->bc_string) - 1;

                $this->barra_html="";

                for ($xi=0; $xi<= $lbc; $xi++)
                {
                    $distancia = 0;
                    //barra preta, barra branca
                    if ($xi % 2 == 0)
                    {
                        # Setando para cor preta
                        $this->objPDF->setStrokeColor(0,0,0);
                    }
                    else
                    {
                        # Setando para cor branca
                        $this->objPDF->setStrokeColor(1,1,1);
                    }

                    if (isset($this->bc_string[$xi]))
                    {
                        if ($this->bc_string[$xi]=="0")
                        {
                            # Setando espessura da barra a ser impressa [Barra FINA]
                            $distancia = $this->ebf;
                        }
                        else
                        {
                            # Setando espessura da barra a ser impressa [Barra GROSSA]
                            $distancia = $this->ebg;
                        }
                    }

                    if ($distancia>0)
                    {
                        # Desenhando as barras
                        for ($bi = 1; $bi <= $distancia; $bi++)
                        {
                            $this->objPDF->rectangle($this->coluna, $this->linha, 0, $this->altb);
                            $this->coluna = $this->coluna + 1;
                        }
                    }
                }
                $this->objPDF->setStrokeColor(0,0,0);
                $this->objPDF->setLineStyle(1);
                return $this->coluna;
                #$this->objPDF->addText($coluna,($this->linha - 14 - $this->altb),7,$this->codigo);
            }
            else
            {
                return 0;
                $this->barra_html = "<B>Código de Barras Indefinido.</B>";
            }
        }

		function desenhaBarcode39($codigo,$coluna,$linha,$altura=50)
		{
			$this->codigo = "*".mb_strtoupper(trim($codigo))."*";
            $this->coluna = (double)$coluna;
            $this->linha = $linha;
            $this->altb = $altura;
			
			$this->ebf = 0.8;
            $this->ebg = 2;
			#$I = 5.3 * $X;
			$C = strlen($this->codigo);
			#$L = ($C+2)*(3*$N+6)*$X+($C+1)*$I+20*$X;  //last bit for the quiet zones
			
			//define barcode patterns
			//n - Estreita    w - Larga
			$this->bc['0'] = "nnnwwnwnn";
			$this->bc['1'] = "wnnwnnnnw";
			$this->bc['2'] = "nnwwnnnnw";
			$this->bc['3'] = "wnwwnnnnn";
			$this->bc['4'] = "nnnwwnnnw";
			$this->bc['5'] = "wnnwwnnnn";
			$this->bc['6'] = "nnwwwnnnn";
			$this->bc['7'] = "nnnwnnwnw";
			$this->bc['8'] = "wnnwnnwnn";
			$this->bc['9'] = "nnwwnnwnn";
			$this->bc['A'] = "wnnnnwnnw";
			$this->bc['B'] = "nnwnnwnnw";
			$this->bc['C'] = "wnwnnwnnn";
			$this->bc['D'] = "nnnnwwnnw";
			$this->bc['E'] = "wnnnwwnnn";
			$this->bc['F'] = "nnnwwnwnn";
			$this->bc['G'] = "nnnnnwwnw";
			$this->bc['H'] = "wnnnnwwnn";
			$this->bc['I'] = "nnwnnwwnn";
			$this->bc['J'] = "nnnnwwwnn";
			$this->bc['K'] = "wnnnnnnww";
			$this->bc['L'] = "nnwnnnnww";
			$this->bc['M'] = "wnwnnnnwn";
			$this->bc['N'] = "nnnnwnnww";
			$this->bc['O'] = "wnnnwnnwn";
			$this->bc['P'] = "nnwnwnnwn";
			$this->bc['Q'] = "nnnnnnwww";
			$this->bc['R'] = "wnnnnnwwn";
			$this->bc['S'] = "nnwnnnwwn";
			$this->bc['T'] = "nnnnwnwwn";
			$this->bc['U'] = "wwnnnnnnw";
			$this->bc['V'] = "nwwnnnnnw";
			$this->bc['W'] = "wwwnnnnnn";
			$this->bc['X'] = "nwnnwnnnw";
			$this->bc['Y'] = "wwnnwnnnn";
			$this->bc['Z'] = "nwwnwnnnn";
			$this->bc['-'] = "nwnnnnwnw";
			$this->bc['.'] = "wwnnnnwnn";
			$this->bc[' '] = "nwwnnnwnn";
			$this->bc['*'] = "nwnnwnwnn";
			$this->bc['$'] = "nwnwnwnnn";
			$this->bc['/'] = "nwnwnnnwn";
			$this->bc['+'] = "nwnnnwnwn";
			$this->bc['%'] = "nnnwnwnwn";
			
			# Setando para cor preta
			$this->objPDF->setStrokeColor(0,0,0);
			$this->objPDF->setColor(0,0,0);
			
			# seta a espessura da linha
			$this->objPDF->setLineStyle(1);
			
			for($i=0;$i<$C;$i++) //loop through the string
			{
				$ic = $this->codigo[$i];
				$bs = $this->bc[$ic];
				$bar = true;
				for($j=0; $j<strlen($bs); $j++) //loop through the matching entry to draw
				{
					$distancia = 0;
					// barra grossa = 3 vezes o tamanho da fina
					if($bs[$j]=='w')
					{
						$distancia = bcmul($this->ebg,$this->ebf,4);
					}
					// barra fina
					if($bs[$j]=='n')
					{
						$distancia = $this->ebf;
					}
					
					if ( $bar ) //drawing a bar
					{
						/*# Desenhando as barras
						for ($bi=1; $bi <= $distancia; $bi++)
						{
							$this->objPDF->rectangle($this->coluna, $this->linha, 0, $this->altb);
							$this->coluna += $this->ebf;
						}*/
						#$this->objPDF->line($this->coluna,$this->linha, $this->coluna, $this->linha+$this->altb);
						$this->objPDF->filledRectangle($this->coluna,$this->linha, $distancia, $this->altb);
						#$this->objPDF->filledRectangle($this->coluna,$this->linha, $distancia-1, $this->altb);
					}
					#$this->coluna += $distancia;
					$this->coluna = bcadd($this->coluna, $distancia, 4);
					
					if($bar) $bar=false;  //turn bars off and on
					else $bar=true;
				}//end of barcode characters
				$this->coluna = bcadd($this->coluna, $this->ebf, 4);
				#$this->coluna += $this->ebf;
				
			}//end of info
			$this->objPDF->setStrokeColor(0,0,0);
			$this->objPDF->setLineStyle(1);
			
			return $this->coluna;
		}
	}
?>