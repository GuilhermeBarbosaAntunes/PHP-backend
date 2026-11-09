<?php
$nome = "Guibas";
$idade = 25;
$cidade = "São Paulo";
echo "meu nome é $nome, tenho $idade anos e moro em $cidade";$dia = 3;

switch ($dia) {
    case 1:
        echo $nome;
        break;
    case 2:
        echo $idade;
        break;
    case 3:
        echo $cidade;
        break;
    default:
    echo "outro lugar";
} 