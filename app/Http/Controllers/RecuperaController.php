<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\Exception;

class RecuperaController extends Controller
{
    /**
     * Função que redireciona para a url correta.
     * Utilizada quando a rota é acessada por um método GET.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index()
    {
        return redirect('/#/recupera');
    }

    /**
     * Função que realiza a recuperação da senha de usuário.
     *
     * @param Request $request
     * @return array
     */
    public function reset(Request $request)
    {
        try {
            /* Instancia o controller de usuário */
            $userController = new UserController;

            /* Verifica se o e-mail informado está cadastrado */
            if ($user = $userController->query('email', $request->input('email'))) {
                /* Instancia o controller de validação */
                $validationController = new ValidationController;

                /* Deleta validações anteriores */
                while ($val = $validationController->query('user_id', $user->id)) {
                    $val->delete();
                }

                /* Gera a senha inicial randomicamente */
                $pass = substr(bcrypt(microtime()), 40, 10);

                /* Cria a validação */
                $validationController->create($pass, $user->id, $user->email, 2);

                /* Envia o e-mail */
                //$this->sendEmail($user->email, $pass);

                return [
                    'codigo' => '0',
                    'objeto' => $user,
                    'mensagem' => "Enviamos uma mensagem para $user->email para a recuperação de sua senha.",
                ];
            } else {
                return [
                    'codigo' => '1',
                    'objeto' => null,
                    'mensagem' => 'E-mail não encontrado.',
                ];
            }
        } catch (Exception $exception) {
            return [
                'codigo' => '1',
                'objeto' => null,
                'mensagem' => $exception->getMessage(),
            ];
        }
    }

    /**
     * Função responsável pelo envio do e-mail de recuperação de senha.
     *
     * @param $email
     * @param $pass
     */
    private function sendEmail($email, $pass)
    {
        $url = url('/#/valida');
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $message = "<table align='center' bgcolor='#FFFFFF' border='0' cellpadding='0' cellspacing='0' style='background:#ffffff;' width='100%'><tbody><tr><td><table align='center' border='0' cellpadding='0' cellspacing='0' class='full' style='padding:0 5px;' width='570'><tbody><tr><td align='center' style='padding:0px 20px 30px 20px;text-align:center;font-size:14px;color:#676a6c;line-height:24px;' valign='middle' width='100%'></td></tr><tr><td height='30' width='100%'>&nbsp;</td></tr><tr><td align='left' style='padding:0 20px;text-align:center;font-size:14px;color:#676a6c;line-height:24px;' valign='middle' width='100%'>Você solicitou a recuperação de senha.<br>Seguem os seus dados para efetivar a alteração:<br><br><span style='line-height:16px;'>LOGIN<br>$email</span><br><br><span style='line-height:16px;'>SENHA<br>$pass</span></td></tr><tr><td height='30' width='100%'>&nbsp;</td></tr><tr><td align='center' style='padding:0 20px;text-align:center;' valign='middle' width='100%'><table align='center' border='0' cellpadding='0' cellspacing='0' class='fullcenter'><tbody><tr style='padding:0 20px;'><td align='center' bgcolor='#4CA4E0' height='45' style='background:#4ca4e0;padding:0 30px;font-weight:600;color:#ffffff;text-transform:uppercase;'><a href='$url' style='color:#ffffff;font-size:14px;text-decoration:none;line-height:24px;width:100%;' target='_blank'>Recuperar minha senha</a></td></tr><tr><td align='center' style='padding:20px 20px;text-align:center;font-size:14px;color:#676a6c;line-height:24px;' valign='middle' width='100%'></td></tr></tbody></table><tr><td align='left' style='padding:0 20px 80px 20px;text-align:center;font-size:14px;color:#676a6c;line-height:24px;' valign='middle' width='100%'>Caso não tenha solicitado, desconsidere essa mensagem.</td></tr>";

        mail($email, 'Recuperação de Senha', $message, $headers);
    }
}
