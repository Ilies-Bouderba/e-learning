<div class="student-courses-page">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 1.5rem;">
        <div class="page-header" style="margin-bottom: 1.5rem;">
            <h1 style="font-family: var(--font-head); font-size: 1.8rem; font-weight: 800; margin-bottom: 0.25rem;">My Learning</h1>
            <p style="color: var(--c-muted); font-size: 0.9rem;">Continue where you left off or discover new courses</p>
        </div>

        @if(session('success'))
            <div class="mc-flash" style="margin-bottom: 1rem;">{{ session('success') }}</div>
        @endif

        <div class="filters-section" style="margin-bottom: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="text" class="mc-search" wire:model.live.debounce.300ms="search" placeholder="Search courses..." style="flex: 1; max-width: 300px; padding: 0.6rem 1rem;">
            <select class="mc-search" wire:model.live="department" style="max-width: 220px; padding: 0.6rem 1rem;">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->icon }} {{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        @if($enrolledCourses->count() > 0)
            <div class="section" style="padding: 2rem 0;">
                <h2 style="font-family: var(--font-head); font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem;">📚 My Courses</h2>
                <div class="courses-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem;">
                    @foreach($enrolledCourses as $course)
                        @php
                            $enrollment = $course->enrollments->first();
                            $progress = $enrollment ? $enrollment->progress_percentage : 0;
                            $chaptersCompleted = $course->chapters->filter(function($chapter) use ($enrollment) {
                                return $chapter->progress->where('student_id', auth()->id())->where('completed', true)->count() > 0;
                            })->count();
                            $totalChapters = $course->chapters->count();
                        @endphp
                        <div class="course-card" style="background: var(--c-bg); border: var(--border); border-radius: var(--radius); padding: 1.25rem; box-shadow: var(--shadow);">
                            <div class="course-card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <span style="font-size: 1.8rem;">{{ $course->icon }}</span>
                                <span class="badge" style="background: var(--c-yellow); padding: 0.2rem 0.6rem; font-size: 0.7rem;">In Progress</span>
                            </div>
                            <h3 style="font-family: var(--font-head); font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $course->title }}</h3>
                            <p style="font-size: 0.8rem; color: var(--c-muted); margin-bottom: 0.75rem;">{{ Str::limit($course->description, 80) }}</p>
                            <div style="margin-bottom: 0.75rem;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.7rem; margin-bottom: 0.25rem;">
                                    <span>Progress</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div style="background: rgba(15,14,23,0.1); height: 5px; border-radius: 3px; overflow: hidden;">
                                    <div style="width: {{ $progress }}%; background: var(--c-yellow); height: 100%;"></div>
                                </div>
                                <div style="font-size: 0.65rem; color: var(--c-muted); margin-top: 0.25rem;">
                                    {{ $chaptersCompleted }}/{{ $totalChapters }} chapters completed
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 0.4rem;">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--c-dark); color: var(--c-yellow); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">
                                        {{ strtoupper(substr($course->teacher->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size: 0.7rem; color: var(--c-muted);">{{ $course->teacher->name }}</span>
                                </div>
                                <a href="{{ route('cours.show', $course) }}" class="btn-sm" style="padding: 0.3rem 0.7rem; font-size: 0.7rem;">Continue →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="section" style="padding: 2rem 0;">
            <h2 style="font-family: var(--font-head); font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem;">✨ Discover New Courses</h2>
            @if($availableCourses->count() > 0)
                <div class="courses-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem;">
                    @foreach($availableCourses as $course)
                        <div class="course-card" style="background: var(--c-bg); border: var(--border); border-radius: var(--radius); padding: 1.25rem; box-shadow: var(--shadow);">
                            <div class="course-card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <span style="font-size: 1.8rem;">{{ $course->icon }}</span>
                                @if($course->hasPassword())
                                    <span class="badge" style="background: var(--c-dark); color: var(--c-bg); padding: 0.2rem 0.6rem; font-size: 0.7rem;">🔒 Locked</span>
                                @else
                                    <span class="badge" style="background: var(--c-yellow); padding: 0.2rem 0.6rem; font-size: 0.7rem;">Open</span>
                                @endif
                            </div>
                            <h3 style="font-family: var(--font-head); font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $course->title }}</h3>
                            <p style="font-size: 0.8rem; color: var(--c-muted); margin-bottom: 0.75rem;">{{ Str::limit($course->description, 80) }}</p>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <div style="display: flex; align-items: center; gap: 0.4rem;">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--c-dark); color: var(--c-yellow); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">
                                        {{ strtoupper(substr($course->teacher->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size: 0.7rem; color: var(--c-muted);">{{ $course->teacher->name }}</span>
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <span style="font-size: 0.65rem; color: var(--c-muted);">{{ $course->chapters->count() }} chapters</span>
                                    <span style="font-size: 0.65rem; color: var(--c-muted);">{{ $course->students->count() }} students</span>
                                </div>
                            </div>
                            <a href="{{ route('student.cours.enroll', $course) }}" class="btn btn-primary" style="width: 100%; text-align: center; justify-content: center; padding: 0.5rem; font-size: 0.8rem;">Enroll Now →</a>
                        </div>
                    @endforeach
                </div>
                <div class="pagination-wrapper" style="margin-top: 1.5rem;">
                    {{ $availableCourses->links() }}
                </div>
            @else
                <div class="empty-state" style="text-align: center; padding: 2rem; background: rgba(15,14,23,0.05); border-radius: var(--radius);">
                    <span style="font-size: 2rem;">📭</span>
                    <p style="margin-top: 0.5rem; color: var(--c-muted); font-size: 0.9rem;">No courses available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>
