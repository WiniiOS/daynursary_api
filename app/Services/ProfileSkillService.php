<?php

namespace App\Services;

use App\Models\JobProfileSkill;

class ProfileSkillService
{
    protected $jobProfileSkillModel;

    public function __construct(JobProfileSkill $jobProfileSkillModel)
    {
        $this->jobProfileSkillModel = $jobProfileSkillModel;
    }

    public function getAllSkills()
    {
        return $this->jobProfileSkillModel->all();
    }

    public function createSkill(array $data)
    {
        return $this->jobProfileSkillModel->create($data);
    }

    public function updateSkill($id, array $data)
    {
        $jobProfileSkill = $this->jobProfileSkillModel->find($id);

        if ($jobProfileSkill) {
            $jobProfileSkill->update($data);
            return $jobProfileSkill;
        }

        return null;
    }

    public function deleteSkill($id)
    {
        $jobProfileSkill = $this->jobProfileSkillModel->find($id);

        if ($jobProfileSkill) {
            $jobProfileSkill->delete();
            return true;
        }

        return false;
    }
}
