<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codnegocioprodutobarra             NOT NULL DEFAULT nextval('tblnegocioprodutobarra_codnegocioprodutobarra_seq'::regclass)
 * @property  bigint                         $codnegocio                         NOT NULL
 * @property  numeric(14,3)                  $quantidade                         NOT NULL
 * @property  numeric(14,3)                  $valorunitario                      NOT NULL
 * @property  numeric(14,2)                  $valortotal                         NOT NULL
 * @property  bigint                         $codprodutobarra                    NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codnegocioprodutobarradevolucao    
 *
 * Chaves Estrangeiras
 * @property  Negocio                        $Negocio                       
 * @property  NegocioProdutoBarra            $NegocioProdutoBarra           
 * @property  ProdutoBarra                   $ProdutoBarra                  
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  NegocioProdutoBarra[]          $NegocioProdutoBarraS
 * @property  CupomFiscalProdutoBarra[]      $CupomFiscalProdutoBarraS
 * @property  EstoqueMovimento[]             $EstoqueMovimentoS
 * @property  NotaFiscalProdutoBarra[]       $NotaFiscalProdutoBarraS
 */

class NegocioProdutoBarra extends MGModel
{
    protected $table = 'tblnegocioprodutobarra';
    protected $primaryKey = 'codnegocioprodutobarra';
    protected $fillable = [
        'codnegocio',
        'quantidade',
        'valorunitario',
        'valortotal',
        'codprodutobarra',
        'codnegocioprodutobarradevolucao',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Negocio()
    {
        return $this->belongsTo(Negocio::class, 'codnegocio', 'codnegocio');
    }

    public function NegocioProdutoBarra()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarradevolucao');
    }

    public function ProdutoBarra()
    {
        return $this->belongsTo(ProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }
    
    public function NegocioProdutoBarraDevolucao()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarradevolucao');
    }    


    // Tabelas Filhas
    public function NegocioProdutoBarraS()
    {
        return $this->hasMany(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarradevolucao');
    }

    public function CupomFiscalProdutoBarraS()
    {
        return $this->hasMany(CupomFiscalProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }

    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }

    public function NotaFiscalProdutoBarraS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }

    public function NegocioProdutoBarraDevolucaoS()
    {
        return $this->hasMany(NegocioProdutoBarra::class, 'codnegocioprodutobarradevolucao', 'codnegocioprodutobarra');
    }
    
    public static function search($id)
    {
        return NegocioProdutoBarra::id($id)
            ->paginate(10);
    }
    
    public function scopeId($query, $id)
    {
        return $query->whereHas('ProdutoBarra', function($q) use ($id) {
            $q->where('codproduto', $id);
        });        
    }    
}
