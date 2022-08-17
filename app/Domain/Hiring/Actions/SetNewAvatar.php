<?php


namespace App\Domain\Hiring\Actions;

class SetNewAvatar
{
    /**
     * @param $data
     * Set New Names for avatar to avoid naming conflicts.
     * @return mixed
     *
     */
    public function execute($data)
    {
        $avatar = $data['avatar'];
        $new_names['avatar'] = time() . $avatar->getClientOriginalName();
        $avatar->move('storage/uploads/applicants/image', $new_names['avatar']);
        return $new_names;
    }
}
