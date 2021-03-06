<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codmarca                           NOT NULL DEFAULT nextval('tblmarca_codmarca_seq'::regclass)
 * @property  varchar(50)                    $marca                              
 * @property  boolean                        $site                               NOT NULL DEFAULT false
 * @property  varchar(1024)                  $descricaosite                      
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  ProdutoBarra[]                 $ProdutoBarraS
 * @property  Produto[]                      $ProdutoS
 */

class Marca extends MGModel
{
    protected $table = 'tblmarca';
    protected $primaryKey = 'codmarca';
    protected $fillable = [
        'codimagem',
        'marca',
        'site',
        'descricaosite',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
        'inativo'
    ];

    public function validate() {
        
        $this->_regrasValidacao = [
            'marca' => 'required|min:10', 
            'descricaosite' => 'required|min:50', 
        ];
    
        $this->_mensagensErro = [
            'marca.required' => 'O campo Marca não pode ser vazio',
            'marca.min' => 'O campo Marca deve ter mais de 9 caracteres',
            'descricaosite.required' => 'O campo Descrição não pode ser vazio',
            'descricaosite.min' => 'O campo Descrição deve ter mais de 39 caracteres',
        ];
        
        return parent::validate();
        
    }
    
    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }
    
    public function Imagem()
    {
        return $this->belongsTo(Imagem::class, 'codimagem', 'codimagem');
    }

    // Tabelas Filhas
    public function ProdutoBarraS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codmarca', 'codmarca');
    }

    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codmarca', 'codmarca')->orderBy('produto');
    }

    // Buscas 
    public static function filterAndPaginate($codmarca, $marca, $inativo)
    {
        return Marca::codmarca(numeroLimpo($codmarca))
            ->marca($marca)
            ->inativo($inativo)
            ->orderBy('marca', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodmarca($query, $codmarca)
    {
        if (trim($codmarca) === '')
            return;
        
        $query->where('codmarca', $codmarca);
    }
    
    public function scopeMarca($query, $marca)
    {
        if (trim($marca) === '')
            return;
        
        $marca = explode(' ', $marca);
        foreach ($marca as $str)
            $query->where('marca', 'ILIKE', "%$str%");
    }
    
    public function scopeInativo($query, $inativo)
    {
        if (trim($inativo) === '')
            $query->whereNull('inativo');
        
        if($inativo == 1)
            $query->whereNull('inativo');

        if($inativo == 2)
            $query->whereNotNull('inativo');
    }
      
    
}