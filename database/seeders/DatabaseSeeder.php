<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AcademicYear;
use App\Models\ExamType;
use App\Models\Region;
use App\Models\District;
use App\Models\School;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\Stream;
use App\Models\Examination;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@emark.test',
            'password' => 'password',
            'role' => 'super_admin',
            'is_active' => true,
            'force_password_change' => false,
        ]);

        // Moderator
        $moderator = User::create([
            'name' => 'Math Moderator',
            'email' => 'moderator@emark.test',
            'password' => 'password',
            'role' => 'moderator',
            'is_active' => true,
            'force_password_change' => false,
        ]);

        // Data Entry
        $dataEntry = User::create([
            'name' => 'John DataEntry',
            'email' => 'dataentry@emark.test',
            'password' => 'password',
            'role' => 'data_entry',
            'is_active' => true,
            'force_password_change' => false,
        ]);

        // Viewer
        User::create([
            'name' => 'Regional Officer',
            'email' => 'viewer@emark.test',
            'password' => 'password',
            'role' => 'viewer',
            'is_active' => true,
            'force_password_change' => false,
        ]);

        // Academic Year
        $year = AcademicYear::create(['year' => 2026, 'description' => 'Academic Year 2026', 'is_active' => true]);

        // Exam Types
        $mock = ExamType::create(['name' => 'Mock Examination', 'code' => 'MOCK', 'description' => 'Regional Mock Exam']);
        $joint = ExamType::create(['name' => 'Joint Examination', 'code' => 'JOINT', 'description' => 'Joint Exam']);
        $preNat = ExamType::create(['name' => 'Pre-National Examination', 'code' => 'PRENAT', 'description' => 'Pre-NECTA Exam']);

        // Region
        $mwanza = Region::create(['name' => 'Mwanza', 'code' => 'MWZ']);
        $shinyanga = Region::create(['name' => 'Shinyanga', 'code' => 'SHY']);

        // Districts
        $ilemela = District::create(['region_id' => $mwanza->id, 'name' => 'Ilemela', 'code' => 'ILM']);
        $nyamagana = District::create(['region_id' => $mwanza->id, 'name' => 'Nyamagana', 'code' => 'NYM']);
        $magu = District::create(['region_id' => $mwanza->id, 'name' => 'Magu', 'code' => 'MGU']);
        $misungwi = District::create(['region_id' => $mwanza->id, 'name' => 'Misungwi', 'code' => 'MSW']);

        // Schools
        $amss = School::create(['district_id' => $ilemela->id, 'name' => 'AMSS', 'code' => 'AMSS', 'registration_number' => 'S.001']);
        $bwiru = School::create(['district_id' => $ilemela->id, 'name' => 'Bwiru Boys', 'code' => 'BWIRU', 'registration_number' => 'S.002']);
        $nyasaka = School::create(['district_id' => $nyamagana->id, 'name' => 'Nyasaka', 'code' => 'NYAS', 'registration_number' => 'S.003']);
        $buswelu = School::create(['district_id' => $nyamagana->id, 'name' => 'Buswelu', 'code' => 'BUSW', 'registration_number' => 'S.004']);
        $maguSec = School::create(['district_id' => $magu->id, 'name' => 'Magu Secondary', 'code' => 'MGUS', 'registration_number' => 'S.005']);

        // Subjects
        $math = Subject::create(['name' => 'Mathematics', 'code' => 'MATH', 'max_marks' => 100]);
        $physics = Subject::create(['name' => 'Physics', 'code' => 'PHY', 'max_marks' => 100]);
        $chemistry = Subject::create(['name' => 'Chemistry', 'code' => 'CHEM', 'max_marks' => 100]);
        $history = Subject::create(['name' => 'History', 'code' => 'HIST', 'max_marks' => 100]);
        $english = Subject::create(['name' => 'English', 'code' => 'ENG', 'max_marks' => 100]);
        $biology = Subject::create(['name' => 'Biology', 'code' => 'BIO', 'max_marks' => 100]);

        // Classes
        $f1 = Classes::create(['name' => 'Form One', 'code' => 'F1', 'level' => 1]);
        $f2 = Classes::create(['name' => 'Form Two', 'code' => 'F2', 'level' => 2]);
        $f3 = Classes::create(['name' => 'Form Three', 'code' => 'F3', 'level' => 3]);
        $f4 = Classes::create(['name' => 'Form Four', 'code' => 'F4', 'level' => 4]);

        // Streams
        $a = Stream::create(['name' => 'A', 'code' => 'A']);
        $b = Stream::create(['name' => 'B', 'code' => 'B']);
        Stream::create(['name' => 'C', 'code' => 'C']);
        Stream::create(['name' => 'D', 'code' => 'D']);

        // Examination
        $exam = Examination::create([
            'name' => 'Regional Mock Examination 2026',
            'academic_year_id' => $year->id,
            'exam_type_id' => $mock->id,
            'region_id' => $mwanza->id,
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-15',
            'status' => 'open',
            'created_by' => $admin->id,
            'description' => 'Regional mock examination for Mwanza region',
        ]);
        $exam->districts()->sync([$ilemela->id, $nyamagana->id, $magu->id]);
        $exam->schools()->sync([$amss->id, $bwiru->id, $nyasaka->id, $buswelu->id, $maguSec->id]);
        $exam->subjects()->sync([$math->id, $physics->id, $chemistry->id, $history->id, $english->id, $biology->id]);
        $exam->classes()->sync([$f3->id, $f4->id]);

        // Candidates
        $candidateNames = ['Joseph', 'Mary', 'Peter', 'Anna', 'John', 'Grace', 'David', 'Sarah', 'Michael', 'Ruth', 'James', 'Esther', 'Daniel', 'Joyce', 'Samuel', 'Faith', 'Paul', 'Lucy', 'Mark', 'Dorothy'];
        $schools = [$amss, $bwiru, $nyasaka, $buswelu, $maguSec];
        $districts = [$ilemela, $ilemela, $nyamagana, $nyamagana, $magu];
        for ($i = 0; $i < 20; $i++) {
            $si = $i % 5;
            \App\Models\Candidate::create([
                'examination_id' => $exam->id,
                'candidate_number' => 'S' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'name' => $candidateNames[$i],
                'gender' => $i % 2 === 0 ? 'male' : 'female',
                'school_id' => $schools[$si]->id,
                'district_id' => $districts[$si]->id,
                'class_id' => $f4->id,
                'stream_id' => $i % 2 === 0 ? $a->id : $b->id,
            ]);
        }

        // Panel
        $panel = \App\Models\Panel::create([
            'examination_id' => $exam->id,
            'subject_id' => $math->id,
            'moderator_user_id' => $moderator->id,
        ]);

        // Panel Data Entry
        \App\Models\PanelDataEntry::create([
            'panel_id' => $panel->id,
            'user_id' => $dataEntry->id,
        ]);

        // Panel Marker
        \App\Models\PanelMarker::create([
            'panel_id' => $panel->id,
            'name' => 'Teacher Mark Smith',
            'phone' => '0755123456',
            'school_id' => $amss->id,
        ]);

        // Assignment
        $assignment = \App\Models\Assignment::create([
            'panel_id' => $panel->id,
            'user_id' => $dataEntry->id,
            'district_id' => $ilemela->id,
            'examination_id' => $exam->id,
            'subject_id' => $math->id,
        ]);
        $assignment->schools()->sync([$amss->id, $bwiru->id]);

        // Notifications
        Notification::create([
            'user_id' => $admin->id,
            'title' => 'Welcome to e-Mark',
            'message' => 'The system has been set up successfully.',
            'type' => 'success',
        ]);
        Notification::create([
            'user_id' => $dataEntry->id,
            'title' => 'New Assignment',
            'message' => 'You have been assigned to Mathematics for Ilemela district.',
            'type' => 'info',
            'link' => route('marks.entry'),
        ]);
    }
}
