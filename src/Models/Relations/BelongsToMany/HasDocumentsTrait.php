<?php namespace ThunderID\Person\Models\Relations\BelongsToMany;

trait HasDocumentsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasDocumentsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN DOCUMENT PACKAGE -------------------------------------------------------------------*/
	public function Documents()
	{
		return $this->belongsToMany('ThunderID\Doclate\Models\Document', 'persons_documents', 'person_id', 'document_id')
					->withPivot('created_at');
	}

	public function ScopeRequiredDocuments($query, $variable)
	{
		return $query->with(['documents' => function($q)use($variable){$q->where('is_required', true)->orderBy($variable, 'asc');}, 'documents.templates']);
	}
}