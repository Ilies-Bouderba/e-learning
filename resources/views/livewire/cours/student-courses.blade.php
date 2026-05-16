<div class="student-courses-page">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
        <div class="page-header" style="margin-bottom: 2rem;">
            <h1 style="font-family: var(--font-head); font-size: 2rem; font-weight: 800;">My Learning</h1>
            <p style="color: var(--c-muted);">Continue where you left off or discover new courses</p>
        </div>

        @if(session('success'))
            <div class="mc-flash">{{ session('success') }}</div>
        @endif

        <!-- Filters -->
        <div class="filters-section" style="margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="text" class="mc-search" wire:model.live.debounce.300ms="search" placeholder="Search courses..." style="flex: 1; max-width: 300px;">
            <select class="mc-search" wire:model.live="department" style="max-width: 220px;">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->icon }} {{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- My Courses Section -->
        @if($enrolledCourses->count() > 0)
            <div class="section" style="margin-bottom: 3rem;">
                <h2 style="font-family: var(--font-head); font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">📚 My Courses</h2>
                <div class="courses-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                    @foreach($enrolledCourses as $course)
                        @php
                            $enrollment = $course->enrollments->first();
                            $progress = $enrollment ? $enrollment->progress_percentage : 0;
                            $chaptersCompleted = $course->chapters->filter(function($chapter) use ($enrollment) {
                                return $chapter->progress->where('student_id', auth()->id())->where('completed', true)->count() > 0;
                            })->count();
                            $totalChapters = $course->chapters->count();
                        @endphp
                        <div class="course-card" style="background: var(--c-bg); border: var(--border); border-radius: var(--radius); padding: 1.5rem; box-shadow: var(--shadow);">
                            <div class="course-card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <span style="font-size: 2rem;">{{ $course->icon }}</span>
                                <span class="badge" style="background: var(--c-yellow);">In Progress</span>
                            </div>
                            <h3 style="font-family: var(--font-head); font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $course->title }}</h3>
                            <p style="font-size: 0.85rem; color: var(--c-muted); margin-bottom: 1rem;">{{ Str::limit($course->description, 80) }}</p>
                            <div style="margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.25rem;">
                                    <span>Progress</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div style="background: rgba(15,14,23,0.1); height: 6px; border-radius: 3px; overflow: hidden;">
                                    <div style="width: {{ $progress }}%; background: var(--c-yellow); height: 100%;"></div>
                                </div>
                                <div style="font-size: 0.7rem; color: var(--c-muted); margin-top: 0.25rem;">
                                    {{ $chaptersCompleted }}/{{ $totalChapters }} chapters completed
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--c-dark); color: var(--c-yellow); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">
                                        {{ strtoupper(substr($course->teacher->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size: 0.75rem; color: var(--c-muted);">{{ $course->teacher->name }}</span>
                                </div>
                                <a href="{{ route('cours.show', $course) }}" class="btn-sm">Continue →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Available Courses Section -->
        <div class="section">
            <h2 style="font-family: var(--font-head); font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">✨ Discover New Courses</h2>
            @if($availableCourses->count() > 0)
                <div class="courses-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                    @foreach($availableCourses as $course)
                        <div class="course-card" style="background: var(--c-bg); border: var(--border); border-radius: var(--radius); padding: 1.5rem; box-shadow: var(--shadow);">
                            <div class="course-card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <span style="font-size: 2rem;">{{ $course->icon }}</span>
                                @if($course->hasPassword())
                                    <span class="badge" style="background: var(--c-dark); color: var(--c-bg);">🔒 Locked</span>
                                @else
                                    <span class="badge">Open</span>
                                @endif
                            </div>
                            <h3 style="font-family: var(--font-head); font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $course->title }}</h3>
                            <p style="font-size: 0.85rem; color: var(--c-muted); margin-bottom: 1rem;">{{ Str::limit($course->description, 80) }}</p>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--c-dark); color: var(--c-yellow); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">
                                        {{ strtoupper(substr($course->teacher->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size: 0.75rem; color: var(--c-muted);">{{ $course->teacher->name }}</span>
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <span style="font-size: 0.7rem; color: var(--c-muted);">{{ $course->chapters->count() }} chapters</span>
                                    <span style="font-size: 0.7rem; color: var(--c-muted);">{{ $course->students->count() }} students</span>
                                </div>
                            </div>
                            <a href="{{ route('student.cours.enroll', $course) }}" class="btn btn-primary" style="width: 100%; text-align: center; justify-content: center;">Enroll Now →</a>
                        </div>
                    @endforeach
                </div>
                <div class="pagination-wrapper" style="margin-top: 2rem;">
                    {{ $availableCourses->links() }}
                </div>
            @else
                <div class="empty-state" style="text-align: center; padding: 4rem; background: rgba(15,14,23,0.05); border-radius: var(--radius);">
                    <span style="font-size: 3rem;">📭</span>
                    <p style="margin-top: 1rem; color: var(--c-muted);">No courses available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>
