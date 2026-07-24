# e-Mark

## Electronic Examination Marking Management System (EEMMS)

### System Specification and Feature Description Document

---

## 1. Introduction

e-Mark is an Electronic Examination Marking Management System designed to coordinate and supervise the complete lifecycle of large-scale examination marking exercises. The system is not intended to mark scripts automatically by computer. Instead, it manages the entire human-driven marking process, including the distribution of work, the recording of marks, the tracking of progress, and the secure and timely release of results.

The platform is built for education authorities, examination boards, regional education offices, and any institution that conducts and coordinates group examinations across multiple schools and districts. It brings structure, accountability, and transparency to an activity that is traditionally paper-based, fragmented, and difficult to monitor.

This document describes every component of the system in full. It defines what each feature does and how the parts relate to one another. It contains no source code; it is purely a description of the system's structure and behaviour.

---

## 2. Purpose and Objectives

The core purpose of e-Mark is to remove the operational chaos that accompanies large marking exercises and to replace it with a controlled, auditable, and measurable workflow.

The principal objectives of the system are the following. To centralize the administration of examinations so that a single authority can oversee every stage from creation to result release. To formalize the assignment of marking and data-entry responsibilities so that every school and subject has a clearly accountable owner. To provide real-time visibility of progress at examination, subject, district, and school level. To guarantee the integrity of recorded marks through verification and moderation controls. To secure sensitive candidate and result data through authentication, role-based access, and full activity logging. And to deliver accurate, ready-to-use reports at the moment they are required.

---

## 3. System Scope

e-Mark is designed to manage all major categories of coordinated examinations. The system supports Regional Mock examinations, District Mock examinations, Joint Examinations, Pre-National Examinations, Regional Assessments, Common Tests, and Internal Examinations. Each of these exercises follows the same underlying management flow, which allows one platform to serve a wide range of examination formats without modification.

---

## 4. Overall System Flow

The system operates as a sequential pipeline in which each stage depends on the completion of the stages before it. This ordered flow ensures that no marking activity can begin until the necessary structural foundations, registrations, and assignments are in place.

The high-level flow proceeds as follows. The administrator first performs the initial system setup, establishing the foundational reference data. An examination is then created within that setup. Schools are registered against the examination, followed by the subjects to be examined. Candidates are imported into the system. Marking panels are then created, and moderators are assigned to lead them. Within each panel, markers and data-entry officers are assigned to their responsibilities, and user accounts are generated for them.

Once the structure is complete, the operational phase begins. Markers mark the physical scripts manually. Data-entry officers record the resulting marks into the system. Moderators verify the entered marks. Statistics and reports are produced throughout to monitor the exercise. Finally, the examination is closed and the results are exported for release.

The order of the pipeline is: system setup, create examination, register schools, register subjects, import candidates, create panels, assign moderators, assign markers, assign data entry, generate user accounts, manual script marking, marks entry, moderator verification, statistics and reports, close examination, and export results.

---

## 5. User Roles

The system defines a strict hierarchy of user roles. Each role carries a specific set of permissions, and users can only see and act upon the parts of the system granted to their role. This role-based design is the primary mechanism for maintaining accountability and data security.

### 5.1 Super Administrator

The Super Administrator holds the highest level of authority and can perform every function in the system. This role is responsible for overall system supervision, the creation of examinations, and the registration of foundational reference data such as regions, districts, schools, and subjects. The Super Administrator manages all user accounts, opens and closes examinations, performs system backups, and has full access to every report. This role represents the ultimate owner and custodian of the platform.

### 5.2 Examination Administrator

The Examination Administrator is the head of a specific examination. This role manages a single examination in its entirety. Its responsibilities include creating the marking panels for that examination, adding moderators to those panels, monitoring the progress of the exercise, and generating reports specific to the examination it oversees. The Examination Administrator operates within the boundary of one examination rather than across the whole system.

### 5.3 Moderator

Every subject in an examination has its own dedicated Moderator. For example, a single examination may have a Mathematics Moderator, a Physics Moderator, a Biology Moderator, and a History Moderator, each responsible only for their own subject. The Moderator leads the marking panel for their subject. This role adds markers and data-entry officers to the panel, assigns schools and districts to the data-entry officers, approves recorded marks, and monitors the progress of the subject's marking. The Moderator is the quality-control authority for a subject and is responsible for the accuracy of every mark released under it.

### 5.4 Marker

The Marker does not mark within the system. This role performs the physical, manual marking of examination scripts. A Marker receives scripts, marks them by hand, and returns the completed scripts to the Moderator. The system records information about the Marker and their activity, but the actual marking remains a human, paper-based task. The system's function here is to register and track the Marker's participation, not to perform or replace the marking itself.

### 5.5 Data Entry

The Data Entry officer is the most active operational user of the system. This is the role that interacts with the platform most frequently during the recording phase. A Data Entry officer logs into the system, changes their password on first access, views the assignments allocated to them, records marks against candidates, edits those marks while permitted, and monitors their own progress. A critical restriction applies to this role: a Data Entry officer can only see the subject and schools assigned to them and cannot view any other subjects. Marks can be edited only until the Moderator finalizes and locks them, after which they become immutable to this role.

### 5.6 Viewer

The Viewer has read-only access and can only view reports. This role cannot enter, edit, or manage any data. A typical example of a Viewer is a Regional Education Officer who needs oversight of examination outcomes and progress without any operational involvement in the marking process.

---

## 6. Functional Modules

For a professional and maintainable structure, e-Mark is organized into distinct functional modules. Each module encapsulates a coherent area of responsibility, and together they form the complete system. The design is deliberately modular so that new capabilities can be added over time without disturbing the existing foundation.

The system comprises thirteen modules. The Authentication and Security module governs access and protection. The System Setup module manages foundational reference data. The Examination Management module handles the creation and lifecycle of examinations. The Candidate Management module maintains the register of candidates. The Panel Management module organizes marking panels and their members. The User and Role Management module controls accounts and permissions. The Assignment Management module allocates work to users. The Marks Entry Management module records candidate marks. The Verification and Moderation module ensures the integrity of those marks. The Reports and Analytics module produces insight and outputs. The Notifications module keeps users informed of events. The Audit Logs module preserves a record of all activity. And the Backup and Restore module protects against data loss.

Each of these modules is described in detail in the sections that follow.

---

## 7. System Setup Module

System Setup is the foundational module and the first area to be configured before any examination can be created. It establishes the reference data on which the entire system depends. Every other module builds upon the entities defined here.

The Academic Year defines the year in which examinations take place, for example 2026, 2027, or 2028. This anchors each examination to a specific cycle. The Examination Types define the categories of examination the system will manage, such as Mock, Joint, Pre-National, Terminal, Annual, and District Assessment. The Region defines the geographic regions covered, such as Mwanza, Shinyanga, and Simiyu. The District defines the districts within those regions, such as Ilemela, Nyamagana, Magu, and Misungwi. The Schools define the individual institutions participating in examinations, such as AMSS, Bwiru, Nyasaka, and Buswelu. The Subjects define the examinable subjects, such as Mathematics, Physics, Chemistry, History, and English. The Classes define the academic levels, such as Form One, Form Two, Form Three, and Form Four. The Streams define the divisions within a class, such as A, B, C, and D.

Together these entities form a complete structural map of the education environment that the system will coordinate.

---

## 8. Examination Management Module

The Examination Management module governs the creation and lifecycle of each examination. Creating an examination binds together the reference data established in System Setup into a single, defined marking exercise, such as a Mock Examination for the year 2026.

When creating an examination, the administrator specifies the examination name, the academic year, the region, the districts involved, the classes to be examined, the subjects to be covered, the starting date, and the ending date. These parameters define the full boundary of the exercise.

Each examination carries a status that reflects its position in its lifecycle. A Draft examination is still being prepared and is not yet operational. An Open examination is active and available for marking and data entry. A Closed examination has finished its operational phase and no longer accepts changes. An Archived examination has been retained for record-keeping after completion. This status control ensures that activities can only occur when an examination is in the appropriate state.

---

## 9. Candidate Management Module

The Candidate Management module maintains the register of every candidate who will be examined. Candidates can be added in two ways. They can be brought in through an Excel import, which allows large numbers of candidates to be loaded quickly and efficiently, or they can be entered manually one at a time when only small adjustments or additions are required.

For each candidate, the system records a candidate number, the student's name, the student's gender, the school they belong to, the district of that school, and the class they are in. This information links every candidate to the correct school, district, and class so that marks can later be recorded and reported accurately against the right individuals.

---

## 10. Panel Management Module

The Panel Management module is where the human resources of the marking exercise are organized. A Moderator enters this module to build the team for their subject's panel. Within a panel, the Moderator can add both markers and data-entry officers.

When adding a Marker, the Moderator records the teacher's name, phone number, school, and subject. This registers the marker as a recognized participant in the panel and links them to the subject they will mark.

When adding a Data Entry officer, the Moderator records the teacher's name, phone number, email address, username, and password. The system assists this process by generating a username and an initial password for the new user. For instance, the system may create a username derived from the person's name together with a default temporary password. To protect the account, the first login triggers a forced password change, requiring the new user to set their own private password before they can proceed. This ensures that no account remains protected only by a shared default credential.

---

## 11. Assignment Management Module

The Assignment Management module allocates specific work to specific data-entry officers, and it is central to the accountability model of the system. Working through this module, a Moderator selects a Data Entry officer and then defines exactly what that officer is responsible for by choosing a subject, then a district, and then the specific schools within that district.

For example, a Moderator may assign the officer named John to the subject Mathematics, within the district of Ilemela, and specifically to the schools AMSS, Nyasaka, and Buswelu. As a result of this assignment, John will see only those three schools and nothing else. This deliberate narrowing of visibility means each officer works within a clearly bounded scope, which both simplifies their task and prevents any accidental or unauthorized access to data outside their responsibility.

---

## 12. Marks Entry Management Module

The Marks Entry Management module is where recorded marks are entered into the system by data-entry officers, following the manual marking performed by the markers.

The entry process follows a clear path. The officer logs in and arrives at their dashboard. From there they access their assigned subject, then their assigned schools. They choose a specific school, which presents the list of candidates for that school. They then enter marks for each candidate and save the results, and the record is marked as completed.

The marks entry screen displays, for each candidate, the candidate number, the student's name, and a field for the marks. For example, a candidate with the number S0101 and the name Joseph may be recorded with a mark of 74. As entry proceeds, the system displays live progress, such as showing that 120 of 340 candidates have been completed. This continuous progress indicator gives each officer immediate feedback on how much of their assigned work remains.

---

## 13. Verification and Moderation Module

The Verification and Moderation module gives Moderators the tools to oversee and validate the marks recorded within their subject's panel. This module is the guarantee of data integrity in the system.

A Moderator is presented with a comprehensive view of the state of their subject. This includes the total number of schools, the number completed, and the number still pending. It also includes the total number of scripts, the number of scripts remaining, the number of marks entered, and the number of marks verified. These figures give the Moderator a precise understanding of both the volume and the quality status of the work.

Acting on this information, the Moderator can approve marks that are correct, reject marks that contain errors, or return work to the data-entry officer for correction. Approval confirms that the marks are accurate and final. Rejection and return send the work back into the entry process so that mistakes can be fixed before the marks are locked. This approval workflow ensures that no result is released without a deliberate act of verification by the responsible subject authority.

---

## 14. Examination Dashboard

The Examination Dashboard provides a consolidated, at-a-glance view of the entire examination. It summarizes the key structural quantities of the exercise, presenting the number of schools, candidates, subjects, markers, data-entry officers, and moderators involved.

Beyond these totals, the dashboard presents progress visually through graphs. These include an overall progress view for the whole examination, a subject progress view showing how each subject is advancing, a district progress view broken down by district, and a school progress view broken down by individual school. Together these visual indicators allow administrators and moderators to identify bottlenecks, monitor momentum, and direct attention to the areas that are falling behind.

---

## 15. Reports and Analytics Module

The Reports and Analytics module transforms the data captured throughout the exercise into structured, meaningful outputs. The system produces a range of reports, each serving a distinct oversight purpose.

The School Report shows, for each school, the number of candidates, the number completed, and the number still pending. The Subject Report shows, for each subject, the number of marks entered, the number verified, and the number remaining. The Data Entry Performance report shows the productivity of each officer, for example indicating that the officer named John has entered 2,500 records. The Moderator Report shows the number of marks approved, rejected, and still pending under each moderator. The District Report shows, for each district, the number of schools and their completed and pending status. The Overall Examination Report brings everything together into a single summary showing the total candidates, schools, and subjects, along with overall progress and the completion rate.

This suite of reports supports decision-making at every level, from the individual officer's performance up to the overall health of the entire examination.

---

## 16. Security Module

Security is a foundational concern of the system and is enforced through a comprehensive set of controls. Access to the system is governed by login authentication, so that only recognized users may enter. Role permissions ensure that each user can access only the functions appropriate to their role. On first access, a forced password change requires new users to replace their temporary credential with a private one, and a password reset facility allows credentials to be recovered securely when needed.

The system maintains a full audit trail of activity, together with login history and detailed activity logs, so that every action can be traced to its originator. An automatic logout protects unattended sessions, and IP tracking records the origin of access for additional accountability. Regular backups protect the system's data against loss. Together these measures protect the confidentiality, integrity, and availability of sensitive candidate and result information.

---

## 17. Notifications Module

The Notifications module keeps users informed of significant events as they happen, reducing the need for manual follow-up and keeping the exercise moving. The system can issue notifications for events such as the arrival of a new assignment, the completion of a school's marks entry, a change of password, the closing of an examination, and the approval of marks by a moderator. These timely alerts ensure that the right people are aware of the right developments at the right moment.

---

## 18. Audit Logs Module

The Audit Logs module preserves a permanent, detailed record of all activity within the system. It underpins accountability by recording who did what and when, capturing actions, login history, and system events. In the context of examination marking, where the integrity of results is paramount, this immutable record provides both a deterrent against misuse and a means of investigation should any question about a result or an action ever arise.

---

## 19. Backup and Restore Module

The Backup and Restore module protects the system against data loss arising from technical failure, human error, or other disruption. Through regular backups, the system preserves copies of its data that can be restored when required, ensuring that a marking exercise is never irrecoverably lost and that continuity is maintained even in adverse circumstances.

---

## 20. Navigation and Main Menu Structure

The system presents its functionality through a clearly organized main menu that mirrors its modular design. The menu begins with the Dashboard, which provides the primary overview.

The System Setup area contains Academic Years, Examination Types, Regions, Districts, Schools, Subjects, Classes, and Streams. The Examinations area contains options to create an examination and to view active and archived examinations. The Candidates area contains options to import and manage candidates. The Panel Management area contains Moderators, Markers, Data Entry, and Assignments. The Marks Entry area contains Assigned Schools, Marks Entry, and Verification. The Reports area contains Overall Reports, Subject Reports, School Reports, District Reports, and User Performance. The Users area contains User Accounts, Roles, and Permissions. The Settings area contains General Settings, Audit Logs, and Backup.

This structure makes every function discoverable in a logical location and reflects the natural sequence in which the system is used.

---

## 21. Role Relationships and Use Cases

The roles of the system relate to one another in a clear hierarchy of authority and delegation, and each role is associated with a defined set of actions.

The Super Administrator sits at the top and is associated with managing users, performing system setup, creating examinations, generating reports, performing backups, managing permissions, and viewing the dashboard. Beneath the Super Administrator, the Examination Administrator is associated with managing panels, assigning users, monitoring the examination, and generating reports for their examination.

The Moderator, reporting within the structure of an examination, is associated with adding markers, adding data-entry officers, assigning schools, verifying marks, and monitoring progress. The Data Entry officer, working under the Moderator, is associated with logging in, viewing their assignments, entering marks, and editing their own marks while permitted. The Marker, also within the panel, is associated with viewing their assignment and performing manual paper marking. The Viewer, standing outside the operational hierarchy, is associated with viewing reports and printing reports.

This arrangement ensures that authority flows downward from the Super Administrator to operational users, while accountability flows upward from the recording of each individual mark to the overall oversight of the examination.

---

## 22. Scalability and Future Extensions

The modular design of e-Mark makes the system scalable and extensible. Because each area of responsibility is encapsulated within its own module, new capabilities can be added over time without altering the foundation of the system.

Future extensions envisaged for the platform include barcode scanning of scripts to speed and secure the handling of papers, optical character recognition of mark sheets to accelerate the capture of marks, online moderation to allow verification to occur remotely, payment management for markers and data-entry officers to handle their remuneration within the same system, and artificial intelligence analytics to surface deeper insight from examination data. Each of these can be introduced as an additional module built on the existing structure, preserving the stability of the core system while expanding its reach.

---

## 23. Recommended Technology Foundation

For a robust, secure, and maintainable implementation, the system is well suited to a foundation that separates its administrative back end from its data-entry front end. A back end and administrative panel built on a mature, well-supported web framework provides the strength, security, and structure required to manage sensitive examination data, while a cross-platform mobile and web front end provides data-entry officers with a responsive and accessible interface for their work. This combination yields a system that is dependable, secure, and straightforward to expand as the demands placed on examination-marking bodies continue to grow.

---

## 24. Conclusion

e-Mark provides a complete, structured, and secure means of managing large-scale examination marking exercises from beginning to end. It replaces a fragmented, paper-driven, and difficult-to-monitor process with a controlled pipeline in which every role is accountable, every mark is verified, every action is recorded, and every stage is visible in real time. Its modular architecture ensures that it can serve a wide range of examination types today and grow to meet new requirements tomorrow, making it a durable foundation for the coordinated marking work of education authorities and examination boards.
