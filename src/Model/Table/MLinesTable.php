<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MLines Model
 *
 * @method \App\Model\Entity\MLine newEmptyEntity()
 * @method \App\Model\Entity\MLine newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\MLine> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MLine get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\MLine findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\MLine patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\MLine> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MLine|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\MLine saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\MLine>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\MLine>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\MLine>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\MLine> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\MLine>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\MLine>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\MLine>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\MLine> deleteManyOrFail(iterable $entities, array $options = [])
 */
class MLinesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('m_lines');
        $this->setDisplayField('line_name');
        $this->setPrimaryKey('line_cd');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('company_cd')
            ->notEmptyString('company_cd');

        $validator
            ->scalar('line_name')
            ->maxLength('line_name', 256)
            ->notEmptyString('line_name');

        $validator
            ->scalar('line_name_k')
            ->maxLength('line_name_k', 256)
            ->allowEmptyString('line_name_k');

        $validator
            ->scalar('line_name_h')
            ->maxLength('line_name_h', 256)
            ->allowEmptyString('line_name_h');

        $validator
            ->scalar('line_color_c')
            ->maxLength('line_color_c', 8)
            ->allowEmptyString('line_color_c');

        $validator
            ->scalar('line_color_t')
            ->maxLength('line_color_t', 32)
            ->allowEmptyString('line_color_t');

        $validator
            ->scalar('line_type')
            ->maxLength('line_type', 16)
            ->allowEmptyString('line_type');

        $validator
            ->numeric('lon')
            ->allowEmptyString('lon');

        $validator
            ->numeric('lat')
            ->allowEmptyString('lat');

        $validator
            ->allowEmptyString('zoom');

        $validator
            ->allowEmptyString('e_status');

        $validator
            ->integer('e_sort')
            ->allowEmptyString('e_sort');

        return $validator;
    }
}
