<?php
/**
 */
class NuevasController extends AppController
{
    #
    public function __call($slug, $params)
    {
        $this->publicacion($slug);
    }

    #
    public function index()
    {
        $this->publicaciones = (new Api)->get_('publicaciones');

        $this->vistas = (new LiteRecord)->table('vistas_previas')->byArray($this->publicaciones, 'donde_idu');

        $this->notificar = (new LiteRecord)
            ->table('acciones')
            ->where('usuarios_idu=? AND elemento=? AND accion=?')
            ->vals([Session::get('idu'), 'publicaciones', 'notificar'])
            ->byArray($this->publicaciones, 'idu');

        $this->corazones = (new Api)->all_('experiencia', [
            'equal'=> [
                'elemento' => 'publicaciones',
                'de' => Session::get('idu'),
            ],
            'in'=> [
                'para' => ['rows'=>$this->publicaciones, 'by'=>'idu'],
            ],
        ], 'para');
    }

    #
    public function publicacion($slug)
    {
        $this->is_h1 = true;
        $this->pub = (new Api)->get_('publicaciones', slug: $slug);
    }

    #
    public function comentarios($publicaciones_idu)
    {
        $this->publicacion = (new Api)->get_('publicaciones', $publicaciones_idu, 'comentarios', test: 1);
        #_var::die($this->publicacion);
        View::template(null);
    }
}
